<?php
/*
*  PayApi crypter
*  Author: florin
*  Version: 1.0
*  Updated: 20171129
*  License: GPL3
*/
use \Firebase\JWT\JWT;

final class payapi_crypter
{
	//-> @NOTE default @param $encriptionKey 'replace_encription_key_jff6yGSd6FhH' is updated in activation call
	private
	   static $encriptionKey = "replace_encription_key_jff6yGSd6FhH";

	public static function encode($data, $key = false, $crypted = false)
	{
		try {
			$encoded =  self::clean(JWT::encode($data, self::encriptionKey($key)), $key, $crypted);
		} catch (Exception $e) {
			//->
	        $encoded = null;
	    }
	    return $encoded;
	}

	public static function decode($data, $key = false, $crypted = false)
	{
		try {
			$decoded = JWT::decode(self::rebuild($data, $key, $crypted), self::encriptionKey($key), array('HS256'));
		} catch (Exception $e) {
			//->
	        $decoded = null;
	    }
	    return $decoded;
	}

	public static function validate($token)
	{
		//-> @NOTE $token has to be md5() on call
		if ($token === md5(self::token()) || $token === md5(self::pretoken())) {
			return true;
		}
		return false;
	}

	public static function token()
	{
		return md5(date('YmdH', time()) . self::unique());
	}

	private static function unique()
	{
		return md5(self::$encriptionKey . __FILE__ . md5(get_option('admin_email')));
	}

	public static function pretoken()
	{
		return md5(date('YmdH', (time() - 3600)) . self::unique());
	}

	private static function clean($payload, $key = false, $crypted)
	{
		if ($crypted === true) {
			return str_replace(self::prefix($payload, $key), null, $payload);
		}
		return $payload;
	}

	private static function rebuild($payload, $key = false, $crypted)
	{
		if ($crypted === true) {
			return self::prefix($payload, $key) . $payload;
		}
		return $payload;
	}

	private static function prefix($key)
	{
		try {
			$payload = JWT::encode(' ', self::encriptionKey($key));
		} catch (Exception $e) {
			//->
	        $payload = false;
	    }
		if (is_string($payload) === true) {
			return strtok($payload, '.') . '.';
		}
		return null;
	}

	private static function encriptionKey($key)
	{
		if (is_string($key) === true) {
			return $key;
		}
		return self::unique();
	}


}
