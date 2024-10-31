<?php
/*
*  PayApi loader
*  Author: florin
*  Version: 1.0
*  Updated: 20171122
*  License: GPL3
*/
class payapi_loader
{

	public static function library($key)
	{
		$file = payapi_router::library($key);
		if (is_file($file) === true) {
			if (require_once($file)) {
				return true;	
			}
		}
		//-> library file not found
		return false;
	}

	public static function model($key)
	{
		$file = payapi_router::model($key);
		if (is_file($file) === true) {
			if (require_once($file)) {
				return true;	
			}
		}
		//-> library file not found
		return false;
	}

	public static function controller($key)
	{
		$file = payapi_router::controller($key);
		if (is_file($file) === true) {
			if (require_once($file)) {
				return true;	
			}
		}
		//-> library file not found
		return false;
	}

	public static function template($key)
	{
		//->
	}


}
