<?php
/**
 *
 * fetch class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Fetch
{

    private static $_instance;
    protected $_url;
    protected $_method = 'GET';
    protected $_postdata = '';
    protected $_cookies = array();
    protected $_referer;
    protected $_accept = 'text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,image/jpeg,image/gif,*/*';
    protected $_accept_encoding = 'gzip';
    protected $_accept_language = 'en-us';
    protected $_user_agent = 'HttpClient';
    protected $_timeout = 20;
    protected $_stream_timeout = 30;
    protected $_gzip = FALSE;
    protected $_max_reconnects = 5;
    protected $_reconnect_count = 0;
    protected $_max_redirects = 5;
    protected $_redirect_count = 0;
    protected $_username;
    protected $_password;
    protected $_results = FALSE;
    protected $_error;
    protected $_status = 0;


    /**
     * Fetch instance
     * @param string $driver
     * @return Fetch instance
     */
    public static function instance($driver = NULL)
    {
        if (self::$_instance == NULL) {
            if (empty($driver)) {
                $driver = Config::instance()->fetch['driver'];
            }
            self::$_instance = new $driver;
        }
        return self::$_instance;
    }

    public function __construct()
    {
        ;
    }


    public function set($var)
    {
        foreach ( $var as $k => $v )
        {
            if ( isset( $this->$k ) )
            {
                $this->$k = $v;
            }
        }
        return $this;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }

    public function get($url)
    {
    }

    public function post($url, $data = '')
    {
    }

}


class curl_fetch extends Fetch
{

    public function get($url)
    {
        $this->fetch($url);
        return $this->_results;
    }

    public function post($url, $data = '')
    {
        $this->_method = 'POST';
        $this->_url = $url;
        $this->_postdata = $data;
        $this->_request();
        return $this->_results;
    }

    public function fetch($url)
    {
        $this->_method = 'GET';
        $this->_url = $url;
        $this->_request();
    }

    private function _request()
    {
        $headers = array();
        $headers[] = 'Accept: '.$this->_accept;
        $headers[] = 'Connection: Keep-Alive';
        $s = curl_init();
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($s, CURLOPT_HEADER, 1);
        curl_setopt($s,CURLOPT_URL,$this->_url);
        curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout);
        //curl_setopt($s,CURLOPT_MAXREDIRS,$this->_max_redirects);
        curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
        if (stripos($this->_url, 'https') !== FALSE) {
            curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);   //不对认证证书来源的检查
            curl_setopt($s, CURLOPT_SSL_VERIFYHOST, true);    //从证书中检查SSL加密算法是否存在
        }
        //curl_setopt($s,CURLOPT_FOLLOWLOCATION,1);
        //curl_setopt($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation);
        //curl_setopt($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation);
        //curl_setopt($s,CURLOPT_ENCODING , $this->_gzip);

        if(!empty($this->_username) && !empty($this->_password)){
            curl_setopt($s, CURLOPT_USERPWD, $this->_username.':'.$this->_password);
        }
        if ($this->_postdata) {
            curl_setopt($s,CURLOPT_POST,true);
            curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postdata);
        }
        curl_setopt($s,CURLOPT_USERAGENT,$this->_user_agent);
        if ($this->_referer) {
            curl_setopt($s,CURLOPT_REFERER,$this->_referer);
        }
        $response = curl_exec($s);
        curl_close($s);
        $this->_parse_response($response);

    }
    private function _parse_response($response)
    {

        if (empty($response)) {
            return FALSE;
        }
        $hunks = explode("\r\n\r\n", trim($response), 2);
        if (count($hunks) < 2) {
            return FALSE;
        }
        $headers = explode("\r\n", $hunks[0]);
        foreach($headers as $key => $header){
            $key = '';
            $val = '';
            if(  stripos($header, ':') !== FALSE  ){
                list($key, $val) = explode(':', $header, 2);
                $response_header[trim($key)] = trim($val);
            }
        }
        if (!empty($response_header['Location'])) {
            return $this->_redirect($response_header['Location']);
        }
        if (isset($response_header['Content-Encoding']) && $response_header['Content-Encoding'] == 'gzip') {
            $hunks[1] = substr($hunks[1], 10); // See http://www.php.net/manual/en/function.gzencode.php
            $hunks[1] = gzinflate($hunks[1]);
        }
        if(preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]*URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i",$hunks[1],$match))
        {
            return $this->_redirect($match[1]);
        }
        $this->_results = $hunks[1];
    }
    private function _redirect($url)
    {
        if (++$this->_redirect_count >= $this->_max_redirects) {
            $this->_error = 'Number of redirects exceeded maximum ('.$this->max_redirects.')';
            $this->redirect_count = 0;
            return FALSE;
        }
        return $this->fetch($url);
    }


}




class php_fetch extends Fetch
{
    public function get($url)
    {
        $this->fetch($url);
        return $this->_results;
    }

    public function post($url, $data = '')
    {
        $this->_method = 'POST';
        $this->_url = $url;
        $this->_postdata = $data;
        $this->_request();
        return $this->_results;
    }

    public function fetch($url)
    {
        $this->_method = 'GET';
        $this->_url = $url;
        $this->_request();
    }

    private function _request()
    {
        $url = parse_url($this->_url);
        $host = isset($url['host']) ? $url['host'] : '';
        $port = isset($url['port']) ? $url['port'] : 80;
        $path = (isset($url['path']) ? $url['path'] : '/') . (isset($url['query']) ? '?' . $url['query'] : '');
        $scheme = '';
        if ($url['scheme'] == 'https') {
            $scheme = 'ssl://';
            $port = 443;
        }
        $fp = @fsockopen($scheme.$host, $port, $errno, $errstr, $this->_timeout);
        if (!$fp) {
            do {
                if (++$this->_reconnect_count >= $this->_max_reconnects) {
                    $this->_reconnect_count = 0;
                    $this->_error = 'Could not open connection. Error '.$errno.':'.$errstr."\n";
                    return FALSE;
                }
                //echo 'why ? sleep'."\n";
                usleep(100);
                $fp = @fsockopen($scheme.$host, $port, $errno, $errstr, $this->_timeout);

            } while(!$fp);

        }
        $headers = array();
        $headers[] = $this->_method.' '.$path.' HTTP/1.0';
        $headers[] = 'Host: '.$host;
        $headers[] = 'User-Agent: '.$this->_user_agent;
        $headers[] = 'Accept: '.$this->_accept;
        if ($this->_gzip) {
            $headers[] = 'Accept-encoding: '.$this->_accept_encoding;
        }
        $headers[] = 'Accept-language: '.$this->_accept_language;
        if ($this->_referer) {
            $headers[] = 'Referer: '.$this->_referer;
        }
        if (!empty($this->_cookies)) {
            $cookie_headers = array();
            foreach ($array_expression as $key => $value) {
                $cookie_headers[] = $key.'='.urlencode($value).'; ';
            }
            $headers[] = implode('', $cookie_headers);
        }
        if (!empty($this->_username) && !empty($this->_password)) {
            $headers[] = 'Authorization: BASIC '.base64_encode($this->_username.':'.$this->_password);
        }
        if ($this->_postdata) {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Content-Length: '.strlen($this->_postdata);
        }
        $headers[] = 'Connection: Close';
        $request = implode("\r\n", $headers)."\r\n\r\n".$this->_postdata;

        stream_set_timeout($fp, $this->_stream_timeout);
        //fwrite($fp, $request);
        if (fwrite($fp, $request, strlen($request)) === FALSE) {
            fclose($fp);
            $this->_error = "Error writing request type to socket\n";
            return FALSE;
        }
        $response = '';
        while (!feof($fp)) {
            $info = stream_get_meta_data($fp);
            if ($info['timed_out']) {
                $this->_error = "Connection Timed Out!\n";
                return FALSE;
            }
            $response .= fgets($fp, 1024);
        }
        fclose($fp);
        $this->_parse_response($response);

    }
    private function _parse_response($response)
    {

        if (empty($response)) {
            return FALSE;
        }
        $hunks = explode("\r\n\r\n", trim($response), 2);
        if (count($hunks) < 2) {
            return FALSE;
        }
        $headers = explode("\r\n", $hunks[0]);
        foreach($headers as $key => $header){
            $key = '';
            $val = '';
            if(  stripos($header, ':') !== FALSE  ){
                list($key, $val) = explode(':', $header, 2);
                $response_header[trim($key)] = trim($val);
            }
        }
        if (!empty($response_header['Location'])) {
            return $this->_redirect($response_header['Location']);
        }
        if (isset($response_header['Content-Encoding']) && $response_header['Content-Encoding'] == 'gzip') {
            $hunks[1] = substr($hunks[1], 10); // See http://www.php.net/manual/en/function.gzencode.php
            $hunks[1] = gzinflate($hunks[1]);
        }
        if(preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]*URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i",$hunks[1],$match))
        {
            return $this->_redirect($match[1]);
        }
        $this->_results = $hunks[1];
    }

    private function _redirect($url)
    {
        if (++$this->_redirect_count >= $this->_max_redirects) {
            $this->_error = 'Number of redirects exceeded maximum ('.$this->max_redirects.')';
            $this->redirect_count = 0;
            return FALSE;
        }
        return $this->fetch($url);
    }

}