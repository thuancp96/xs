<?php

namespace luk79\CryptoJsAes;

/**
 * Encrypt/Decrypt data from Javascript's CryptoJS
 * PHP 7.x and later supported
 * If you need PHP 5.x support, goto the legacy branch https://github.com/brainfoolong/cryptojs-aes-php/tree/legacy
 * @link https://github.com/brainfoolong/cryptojs-aes-php
 * @version 2.1.1
 */
class CryptoJsAes
{
    /**
     * Encrypt any value
     * @param mixed $value Any value
     * @param string $passphrase Your password
     * @return string
     */
    public static function encrypt($value, string $passphrase)
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = ["ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt)];
        return json_encode($data);
    }

    /**
     * Decrypt a previously encrypted value
     * @param string $jsonStr Json stringified value
     * @param string $passphrase Your password
     * @return mixed
     */
    public static function decrypt(string $jsonStr, string $passphrase)
    {
        $json = json_decode($jsonStr, true);
        $salt = hex2bin($json["s"]);
        $iv = hex2bin($json["iv"]);
        $ct = base64_decode($json["ct"]);
        $concatedPassphrase = $passphrase . $salt;
        $md5 = [];
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($data, true);
    }

    /** 
     *-------------PHP code example-----------------
    */
    /**
     * Decrypt data from a CryptoJS json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $jsonString
     * @return mixed
     */
    public static function cryptoJsAesDecrypt($passphrase, $jsonString){
        $jsondata = json_decode($jsonString, true);
        $salt = hex2bin($jsondata["s"]);
        $ct = base64_decode($jsondata["ct"]);
        $iv  = hex2bin($jsondata["iv"]);
        $concatedPassphrase = $passphrase.$salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);

        $data = str_replace('\n','',$data);
        $data = preg_replace('/\r|\n/','\n',trim($data));
        $string = preg_replace("/[\r\n]+/", " ", $data);
        $json = utf8_encode($string);
        $json = json_decode($json,true);

        // var_dump(json_decode($json,true));
        
        // var_dump(static::json_decode_nice($data)) ; // works!

        // var_dump(json_decode(chop($data,";"),true));
        try{
            $json = json_decode($json,true);
        }catch(\Exception $ex){

        }
        return $json;
    }

    public static function json_decode_nice($json, $assoc = TRUE){
        $json = str_replace("\n","\\n",$json);
        $json = str_replace("\r","",$json);
        $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$json);
        $json = preg_replace('/(,)\s*}$/','}',$json);
        return json_decode($json,$assoc);
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    public static function cryptoJsAesEncrypt($passphrase, $value){
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx.$passphrase.$salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
        return json_encode($data);
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $value
     * @return string
     */
    public static function base64_encode_str($value){
        return base64_encode($value);
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $value
     * @return string
     */
    public static function base64_decode_str($value){
        return base64_decode($value);
    }

    public static function encryptData($data){
        return $data;
        return json_decode(CryptoJsAes::cryptoJsAesEncrypt(env('keyii', 'd4f137fc2e43fbac74b031e843e84f6a'),$data), true);
    }

    public static function decryptRequest($request){
        return (object)json_decode($request->getContent(),true);
        return (object)CryptoJsAes::cryptoJsAesDecrypt(env('keyii', 'd4f137fc2e43fbac74b031e843e84f6a'),$request->getContent());
    }
}