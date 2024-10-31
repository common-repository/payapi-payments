<?php
/*
*  PayApi router
*  Author: florin
*  Version: 1.0
*  Updated: 20171122
*  License: GPL3
*/
final class payapi_router
{

	private static $api_version = 'v1';
	private static $ui_version  = 'v1';
	private static $api         = array(
		"production" => 'input.payapi.io',
		"staging"    => 'staging-input.payapi.io'
	);
	private static $root        = array(
		"dir"        => PAYAPI_DIR,
		"url"        => PAYAPI_URL
	);

	public static function si($url = false)
	{
		return self::root($url) . 'si' . '/';
	}

	public static function ui($url = false)
	{
		return self::root($url) . 'ui' . '/' . self::$ui_version . '/';
	}
	//-> NOTE: returns dir
	public static function model($model)
	{
		return self::si() . 'model' . DIRECTORY_SEPARATOR . 'model' . '.' . $model . '.' . 'php';
	}
	//-> NOTE: returns dir
	public static function controller($model)
	{
		return self::si() . 'controller' . DIRECTORY_SEPARATOR . 'controller' . '.' . $model . '.' . 'php';
	}
	//-> NOTE: returns dir
	public static function schema($key)
	{
		return self::si() . 'schema' . DIRECTORY_SEPARATOR . 'schema' . '.' . $key . '.' . 'json';
	}
	//-> NOTE: returns dir
	public static function view($key)
	{
		return self::ui() . 'view' . DIRECTORY_SEPARATOR;
	}
	//-> NOTE: returns dir
	public static function debug()
	{
		return self::root() . 'debug' . DIRECTORY_SEPARATOR . 'payapi' . '.' . 'debug' . '.' . 'log';
	}
	//-> NOTE: returns url
	private static function js($key, $minimize = false)
	{
		return self::ui(true) . 'js' . DIRECTORY_SEPARATOR . self::minimized($minimize) . $key . '.' . 'js';
	}
	//-> NOTE: returns url
	private static function css($key, $minimize = false)
	{
		return self::ui(true) . 'css' . DIRECTORY_SEPARATOR . self::minimized($minimize) . $key . '.' . 'js';
	}

	private static function minimized($minimize)
	{
		if ($minimize === true) {
			return '.' . 'min';
		}
		return null;
	}

	private static function root($url = false)
	{
		if ($url !== true) {
			$root = self::$root['dir'];
		} else {
			$root = self::$root['url'];
		}
		return $root;
	}

	private static function api($mode = 1)
	{
		if($mode != 0) {
			$api = self::$api['production'];
		} else {
			$api = self::$api['staging'];
		}
		$api_root = 'https://' . $api . '/' . self::$api_version . '/';
		return $api_root;
	}

	public static function end_point($public_id, $staging = 0, $instant = false)
	{
		if ($instant !== true) {
			$form = 'secureform' . '/' . $public_id;
		} else {
			$form = 'webshop' . '/';
		}
		
        $end_point = self::api($staging) . $form;
        return $end_point;
	}

	public static function settings($public_id, $staging = 0)
	{
		return self::api($staging) . 'api' . '/' . 'merchantSettings' . '/' . $public_id;
	}


}
