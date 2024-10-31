<?php 

/**
 * @package payApi
 */
/*
Plugin Name: PayApi Payments
Plugin URI: https://payapi.io
Description: PayApi Payments allows you to include a functional payment button in any of your WP pages, in order your clients are able to buy products and/or services easily. Boost your sales, donations, etcetera. PCI Level 1 certified platform, antifraud checking and maximum security.
Author: florin
Version: 1.0
Author URI: https://www.josebamirena.com/
License: GPLv2
Text Domain: payapi
*/

//-> blocks file direct call
if (function_exists('add_action') !== true) {
	echo 'Please, do not call me directly, better to call my PA ;)';
	exit;
}
//-> definitions
define('PAYAPI_VERSION', '1.0.0');
define('PAYAPI_UI_VERSION', 'v1');
//->
define('PAYAPI_DIR',         plugin_dir_path(__FILE__));
define('PAYAPI_URL',         plugin_dir_url(__FILE__));
define('PAYAPI_BASE',        plugin_basename( __FILE__ ));
define('PAYAPI_LOAD_JWT',    PAYAPI_DIR . 'si' . DIRECTORY_SEPARATOR . 'jwt' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload' . '.' . 'php');
//-> loads JWT
if (is_file(PAYAPI_LOAD_JWT) !== true) {
	echo '[payapi][error] Cannot load JWT module';
	exit;
}
require_once(PAYAPI_LOAD_JWT);
//-> loads core files
foreach(glob(PAYAPI_DIR . 'si' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'core' . '.' . '*' . '.' . 'php') as $core) {
    require_once $core;
}
//-> settings core class
$payapi_settings = new payapi_settings();
//-> debugger core class
$payapi_debug = new payapi_debugger(false);
$payapi_debug->add('PayApi wakeup');
add_action('init', array( 'payapi_engine', 'init'));
//-> installation hooks
register_activation_hook(__FILE__, array( 'payapi_engine', 'plugin_activation'));
register_deactivation_hook(__FILE__, array('payapi_engine', 'plugin_deactivation'));
//-> load main controller
if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
	payapi_loader::controller('backend');
	add_action('init', array( 'payapi_controller_backend', 'init'));
} else {
	if (is_string($payapi_settings->get('public_id')) === true) {
		payapi_loader::controller('frontend');
		add_action('init', array( 'payapi_controller_frontend', 'init'));
	}
}
//-> page render buffer callback
function payapi_buffer_callback($buffer)
{
	$flag = '[(payapi-button)';
	if (strpos($buffer, $flag) != false) {
		return payapi_controller_frontend::translate_payment_button($buffer);
	}
	return $buffer;
}


 