<?php
/**
 *
 * response class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */

class Response
{
	private static $_instance;

    protected static $http_status = array(
                    	100 => "HTTP/1.1 100 Continue",
                    	101 => "HTTP/1.1 101 Switching Protocols",
                    	200 => "HTTP/1.1 200 OK",
                    	201 => "HTTP/1.1 201 Created",
                    	202 => "HTTP/1.1 202 Accepted",
                    	203 => "HTTP/1.1 203 Non-Authoritative Information",
                    	204 => "HTTP/1.1 204 No Content",
                    	205 => "HTTP/1.1 205 Reset Content",
                    	206 => "HTTP/1.1 206 Partial Content",
                    	300 => "HTTP/1.1 300 Multiple Choices",
                    	301 => "HTTP/1.1 301 Moved Permanently",
                    	302 => "HTTP/1.1 302 Found",
                    	303 => "HTTP/1.1 303 See Other",
                    	304 => "HTTP/1.1 304 Not Modified",
                    	305 => "HTTP/1.1 305 Use Proxy",
                    	307 => "HTTP/1.1 307 Temporary Redirect",
                    	400 => "HTTP/1.1 400 Bad Request",
                    	401 => "HTTP/1.1 401 Unauthorized",
                    	402 => "HTTP/1.1 402 Payment Required",
                    	403 => "HTTP/1.1 403 Forbidden",
                    	404 => "HTTP/1.1 404 Not Found",
                    	405 => "HTTP/1.1 405 Method Not Allowed",
                    	406 => "HTTP/1.1 406 Not Acceptable",
                    	407 => "HTTP/1.1 407 Proxy Authentication Required",
                    	408 => "HTTP/1.1 408 Request Time-out",
                    	409 => "HTTP/1.1 409 Conflict",
                    	410 => "HTTP/1.1 410 Gone",
                    	411 => "HTTP/1.1 411 Length Required",
                    	412 => "HTTP/1.1 412 Precondition Failed",
                    	413 => "HTTP/1.1 413 Request Entity Too Large",
                    	414 => "HTTP/1.1 414 Request-URI Too Large",
                    	415 => "HTTP/1.1 415 Unsupported Media Type",
                    	416 => "HTTP/1.1 416 Requested range not satisfiable",
                    	417 => "HTTP/1.1 417 Expectation Failed",
                    	500 => "HTTP/1.1 500 Internal Server Error",
                    	501 => "HTTP/1.1 501 Not Implemented",
                    	502 => "HTTP/1.1 502 Bad Gateway",
                    	503 => "HTTP/1.1 503 Service Unavailable",
                    	504 => "HTTP/1.1 504 Gateway Time-out"
	);



    /**
	 * 构造函数
	 */
	private function __construct()
	{
	}

	public static function instance()
	{
		 if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
	}

	/**
	 * 检查HTTP头信息是否已经发送
	 *
	 * @return bool
	 */
	public function isSend()
	{
		return headers_sent();
	}

	/**
	 * 单独发送一个HTTP头信息
	 *
	 * @param unknown_type $header
	 * @param unknown_type $code
	 */
	public function sendStatus($code = 200)
	{
	    if (!$this->isSend()) {
	        header(self::$http_status[$code]);
	    }

	}

	/**
	 * 跳转 301
	 * @param unknown_type $url
	 * @author andsky 669811@qq.com
	 */
	public function redirect($url)
	{
	    $this->nocache();
	    $this->sendStatus(301);
	    header('Location: ' . $url);
	    exit;
	}

	public function nocache()
	{
	    header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	    header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	    header( 'Cache-Control: post-check=0, pre-check=0', false );
	    header( 'Pragma: no-cache' );
	}



}