<?php
/*
*  PayApi branding
*  Author: florin
*  Version: 1.0
*  Updated: 20171122
*  License: GPL3
*/
final class payapi_branding
{

	private
	   static	$brand = array(
					"partnerId"            => 'payapi',
					"partnerName"          => 'PayApi',
					"partnerSlogan"        => 'Secure Mobile and Online Payments',
					"partnerLogoUrl"       => 'https://cdn.payapi.io/v1/image/payapi/logo_transparent.png',
					"partnerIconUrl"       => 'https://cdn.payapi.io/v1/image/payapi/payapi_shield_protected_v2.jpg',
					"partnerSupportInfoL1" => 'For any support requests or help, please do not hesitate to contact <strong>PayApi Support</strong> via <a href="https://payapi.io">payapi.io</a> or via email: support@payapi.io.',
					"partnerSupportInfoL2" => '',
					"webshopBaseDomain"    => 'multimerchantshop.com',
					"partnerWebUrl"        => 'https://payapi.io',
					"partnerContactEmail"  => 'support@payapi.io',
					"partnerContactPhone"  => 447441908135,
					"updated_at"           => 20171122104941,
					"created_at"           => 20161027100000,
					"updatable"            => 1,
					"enable"               => 1
				);

	public static function get($key = false)
	{
		if ($key === false) {
			return self::$brand;
		} else if (isset(self::$brand[$key]) === true) {
			return self::$brand[$key];
		}
		return null;
	}


}

