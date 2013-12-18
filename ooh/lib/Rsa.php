<?php

/**
 *
 * rsa class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Rsa
{

    private static $_instance;


    function __construct ()
    {
    }


    function __destruct ()
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
     * 生成
     * @param unknown_type $bits
     * @author andsky 669811@qq.com
     */
    public function create($bits = 1024)
    {
        $rsa = openssl_pkey_new(array('private_key_bits' => $bits,'private_key_type' => OPENSSL_KEYTYPE_RSA));
        while( ( $e = openssl_error_string() ) !== false ){
            echo "openssl_pkey_new: " . $e . "\n";
        }
        openssl_pkey_export($rsa, $privatekey);
        while( ( $e = openssl_error_string() ) !== false ){
            echo "openssl_pkey_new: " . $e . "\n";
        }
        $publickey = openssl_pkey_get_details($rsa);
        $publickey = $publickey['key'];
        return array(
                'privatekey' => $privatekey,
                'publickey' => $publickey
                );
    }


    /**
     * 公匙加密
     * @param unknown_type $sourcestr
     * @param unknown_type $publickey
     * @author andsky 669811@qq.com
     */
    function publickey_encodeing($sourcestr, $publickey)
    {

        $pubkeyid = openssl_get_publickey($publickey);
        //if (openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid, OPENSSL_PKCS1_PADDING))
        //{
            //return $crypttext;
        //}
        $result  = '';
        foreach (str_split($sourcestr, 117) as $chunk) {
            openssl_public_encrypt($chunk, $crypttext, $pubkeyid, OPENSSL_PKCS1_PADDING);
            $result .= $crypttext;
        }
        openssl_free_key($pubkeyid);
        return $result;
    }


    /**
     * 公匙解密
     * @param unknown_type $crypttext
     * @param unknown_type $publickey
     * @author andsky 669811@qq.com
     */
    function publickey_decodeing($crypttext, $publickey)
    {
        $pubkeyid = openssl_get_publickey($publickey);
        if (openssl_public_decrypt($crypttext, $sourcestr, $pubkeyid, OPENSSL_PKCS1_PADDING))
        {
            return $sourcestr;
        }
        return FALSE;
    }

    /**
     * 私匙解密
     * @param unknown_type $crypttext
     * @param unknown_type $privatekey
     * @author andsky 669811@qq.com
     */
    function privatekey_decodeing($crypttext, $privatekey)
    {

        $prikeyid = openssl_get_privatekey($privatekey);
        //if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, OPENSSL_PKCS1_PADDING))
        //{
            //return $sourcestr;
        //}
        $result  = '';
        for($i = 0; $i < strlen($crypttext)/128; $i++  ) {
            $data = substr($crypttext, $i * 128, 128);
            openssl_private_decrypt($data, $sourcestr, $prikeyid, OPENSSL_PKCS1_PADDING);
            $result .= $sourcestr;
        }
        openssl_free_key($prikeyid);
        return $result;
    }


    /**
     * 私匙加密
     * @param unknown_type $sourcestr
     * @param unknown_type $privatekey
     * @author andsky 669811@qq.com
     */
    function privatekey_encodeing($sourcestr, $privatekey)
    {

        $prikeyid = openssl_get_privatekey($privatekey);
        if (openssl_private_encrypt($sourcestr, $crypttext, $prikeyid, OPENSSL_PKCS1_PADDING))
        {
            return $crypttext;
        }
        return FALSE;
    }

    function sign($sourcestr, $privatekey)
    {
        $pkeyid = openssl_get_privatekey($privatekey);
        openssl_sign($sourcestr, $signature, $pkeyid);
        openssl_free_key($pkeyid);
        return $signature;

    }

    function verify($sourcestr, $signature, $publickey)
    {
        $pkeyid = openssl_get_publickey($publickey);
        $verify = openssl_verify($sourcestr, $signature, $pkeyid);
        openssl_free_key($pkeyid);
        return $verify;

    }


}