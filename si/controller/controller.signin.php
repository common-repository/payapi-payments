<?php
/*
*  PayApi signin rest api controller
*  Author: florin
*  Version: 1.0
*  Updated: 20171129
*  License: GPL3
*/
class payapi_controller_signin
{
	private $admin = false;

	public static function login($request = false)
	{
		if($request != false && payapi_crypter::validate(md5(sanitize_text_field($request->get_param('token')))) === true) {
			global $payapi_settings;
			$request->get_param('token');
			//-> TODO filter/sanitize
			$public_id = sanitize_text_field($request->get_param('public_id'));
			$api_key = sanitize_text_field($request->get_param('api_key'));
			$mode = sanitize_text_field($request->get_param('token'));
			$merchant_settings = payapi_engine::payapi_get_merchant_settings($public_id, $api_key, $mode);
			if (is_array($merchant_settings) === true && isset($merchant_settings['data']) === true && isset($merchant_settings['code']) === true && $merchant_settings['code'] === 200) {
				$coded_api_key = payapi_crypter::encode($api_key, $public_id, true);
				$update = $payapi_settings->get('public_id');
				if(is_string($update) === true) {
					$payapi_settings->update('public_id', $public_id);
					$payapi_settings->update('api_key', $coded_api_key);
					$payapi_settings->update('mode', $mode);
				} else {
					$payapi_settings->set('public_id', $public_id);
					$payapi_settings->set('api_key', $coded_api_key);
					$payapi_settings->set('mode', $mode);				
				}
				$code = 200;
			} else {
				$code = 403;
			}
		} else {
			$code = 404;
		}
		return payapi_response::get($code);
	}


}
