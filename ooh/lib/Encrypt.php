<?php
/**
 *
 * encrypt class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */

class Encrypt
{
    private static $_instance;

    /**
     * Encrypt instance
     * @return Encrypt instance
     */
    public static function instance()
    {
        if(self::$_instance == NULL){
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function decrypt($encryptedText)
    {
        $cryptText = base64_decode($encryptedText);
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, Config::instance()->encrypt['secret'], $cryptText, MCRYPT_MODE_ECB, $iv);
        return $decryptText;
    }


    public function encrypt($plainText)
    {
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, Config::instance()->encrypt['secret'], $plainText, MCRYPT_MODE_ECB, $iv);
        return base64_encode(rtrim($encryptText, "\0"));
    }

}