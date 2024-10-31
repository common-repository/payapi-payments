<?php
/*
*  PayApi frontend controller
*  Author: florin
*  Version: 1.0
*  Updated: 20171129
*  License: GPL3
*/
class payapi_controller_frontend
{
	private $cont = 0;

	public static function init()
	{
		self::hooks();
	}

	public static function hooks()
	{
		//-> note: this will check the full html buffer for frontend payment buttons to translate
		add_filter('template_redirect', array('payapi_controller_frontend', 'payapi_buffer_handler'), 0);
	}

	public static function payapi_buffer_handler() {
		ob_start('payapi_buffer_callback');
	}

	public static function translate_payment_button($buffer)
	{
		global $payapi_settings;
		$public_id = $payapi_settings->get('public_id');
		$api_key = $payapi_settings->get('api_key');
		$mode = $payapi_settings->get('mode');
		preg_match_all('/\[\(payapi-button\)\((.*?)\)\]/', $buffer, $matches);
		for($cont=0; $cont<count($matches[1]); $cont++) {
			$query = str_replace(',', '&', $matches[1][$cont]);
			parse_str($query, $data);
			//-> @TODO validate button schema
			$shippingInCents = round($data['shipping'], 2) * 100;
			$priceInCentsExcVat = (round($data['price'], 2) * 100);
			$vatInCents = round($data['taxes'], 2) * 100;
			$priceInCentsIncVat = $priceInCentsExcVat + $vatInCents;
			$build = array(			
				"order" => array(
					"sumInCentsIncVat"        => ($priceInCentsIncVat + $shippingInCents),
					"sumInCentsExcVat"        => ($priceInCentsExcVat + $shippingInCents),
					"vatInCents"              => $vatInCents,
					"currency"                => $data['currency'],
					"referenceId"             => self::get_payment_reference()
				),
				"products" => self::product($data['name'], $priceInCentsExcVat, $vatInCents, $data['quantity'], $data['image'])
			);
			//-> handling shipping and handling
			$shipped = self::shipping($build, $data);
			//-> gets api_key
			$decoded_api_key = payapi_crypter::decode($api_key, $public_id, true);
			$payload = payapi_crypter::encode($shipped, $decoded_api_key);
			$end_point = payapi_router::end_point($public_id, $mode);
			$button = self::button($data['name'], $payload, $end_point);
			$buffer = str_replace($matches[0][$cont], $button, $buffer);

		}
		return $buffer;
	}

	private static function shipping($build, $data)
	{
		if(isset($data['shipping']) === true && is_numeric($data['shipping']) && $data['shipping'] > 0) {
			$shippingInCents = round($data['shipping'], 2) * 100;
			$shipping = self::product('shipping and handling', $shippingInCents, 0, 1);
			if(is_array($shipping) === true) {
				$build['products'] = array_merge($build['products'], $shipping);
			}
		}
		return $build;
	}

	private static function product($name, $priceInCentsExcVat, $vatInCents, $quantity, $image = null)
	{
		 $product = array();
		 $priceInCentsIncVat = $priceInCentsExcVat + $vatInCents;
		 $product[] = array(
			"quantity"            => (int) $quantity,
			"title"               => $name,
			"priceInCentsIncVat"  => $priceInCentsIncVat,
			"priceInCentsExcVat"  => $priceInCentsExcVat,
			"vatInCents"          => $vatInCents,
			"vatPercentage"       => self::calculate_vat_percentage($priceInCentsExcVat, $vatInCents),
			"imageUrl"            => $image
		);
		 return $product;
	}

	public static function button($title, $payload, $end_point)
	{
		//-> isolate forms (could be multiple buttons!)
		$isolated = md5($payload);
		$button = "<form role='payapi' action='" . $end_point . "' id='pa-" . $isolated . "' name='pa-" . $isolated . "' method='post' target='_blank'><button type='submit' class='payapi-payment'>" . $title . "</button><input type='hidden' id='data' name='data' value='" . $payload . "'></form>";
		return $button;
	}

	public static function get_payment_reference()
	{
		global $payapi_settings;
		$public_id = $payapi_settings->get('public_id');
		return $public_id . '-' . md5(rand('9999999','9999999999') . date('YmdHis', time()));
	}

	public static function 	calculate_vat_percentage($totalExcVat, $vat)
	{
		$pecentage = round((($vat*100)/$totalExcVat) + 0, 2);
		return $pecentage;
	}


}
