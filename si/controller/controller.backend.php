<?php
/*
*  PayApi backend controller
*  Author: florin
*  Version: 1.0
*  Updated: 20171129
*  License: GPL3
*/

class payapi_controller_backend
{

	private $admin = false;
	private static $cdn = 'https://cdn.payapi.io/';
	private static $minimized = '.min'; //-> .min

	public static function init()
	{
		self::styles();
		self::hooks();
	}

	public static function hooks()
	{
		global $payapi_settings;
		add_action('admin_notices', array('payapi_controller_backend', 'display_notice'));
		add_action('admin_menu', array('payapi_controller_backend', 'add_backend_menu_entry'));
		add_filter( "plugin_action_links_" . PAYAPI_BASE, array('payapi_controller_backend', 'add_settings_link'));
		if(is_string($payapi_settings->get('public_id')) === true) {
			add_filter("mce_external_plugins", array('payapi_controller_backend', 'add_payment_button'));
			add_filter("mce_buttons", array('payapi_controller_backend', 'register_editor_buttons'));
		}
	}

	private static function styles()
	{
		$media = payapi_router::ui(true);
		wp_register_style('style.css', payapi_router::ui(true) . 'css/style.css', array(), PAYAPI_VERSION);
		//wp_register_style('style.css', 'https://cdn.payapi.io/v1/css/wp/style.css', array(), PAYAPI_VERSION);
		wp_enqueue_style('style.css');
		wp_register_style('animate.min.css', payapi_router::ui(true) . 'lib/animate/3.5.1/animate' . self::$minimized . '.css', array(), PAYAPI_VERSION);
		wp_enqueue_style('animate.min.css');
		wp_register_script('wow.min.js', payapi_router::ui(true) . 'lib/wow/1.1.3/wow' . self::$minimized . '.js', array(), PAYAPI_VERSION);
		wp_enqueue_script('wow.min.js', array('jquery'));
		wp_register_script('ui.js', payapi_router::ui(true) . 'js/ui' . self::$minimized . '.js', array(), PAYAPI_VERSION);
		//wp_register_script('ui.js', 'https://cdn.payapi.io/v1/js/wp/ui.js', array(), PAYAPI_VERSION);
		wp_enqueue_script('ui.js', array('jquery'));
	}

	//-> @TODO DOOOOOING!
	public static function display_notice()
	{
		global $payapi_settings;
		$public_id = $payapi_settings->get('public_id');
		if (is_string($public_id) !== true || md5('/wp-admin/plugins.php') === md5(addslashes(strtok(getenv('REQUEST_URI'), '?'))) || isset($_GET['page']) === true && md5(addslashes($_GET['page'])) == md5('payment-payments-info')) {
			payapi_engine::view('backend.signin');
		} else {
			payapi_engine::view('backend.payment');
		}
	}

	public static function add_settings_link($links)
	{
	    $settings_link = '<a href="javascript:payapi_js.setup()">' . __('Settings') . '</a>';
	    array_push( $links, $settings_link );
	  	return $links;
	}

	public static function add_backend_menu_entry()
	{
	        add_menu_page('Payment Payments', 'PayApi', 'manage_options', 'payment-payments-info', array('payapi_controller_backend', 'plugin_info_page'), 'dashicons-info');
	}
	 
	public static function plugin_info_page()
	{
		global $payapi_settings;
		if (is_string($payapi_settings->get('public_id')) === true) {
			$icon  = 'dashicons-yes';
			$style = 'success';
			$text  = 'Your ' . payapi_branding::get('partnerName') . ' account has been verified successfully';
		} else {
			$icon  = 'dashicons-no-alt';
			$style = 'fail';
			$text  = 'You need a valid ' . payapi_branding::get('partnerName') . ' FREE account';
		}
		$status = array(
			"icon"      => $icon,
			"style"     => $style,
			"text"      => $text
		);
		$arguments = array (
			"status"          => $status,
			"pa_sample"       => payapi_router::ui(true) . 'img/sample-payment-button.png'
		);
		payapi_engine::view('backend.info',$arguments);
	}

	public static function add_payment_button($plugin_array)
	{
	    $plugin_array["payapi_payment_button"] =  payapi_router::ui(true) . 'js' . '/' . "button" . self::$minimized . ".js";
	    return $plugin_array;
	}

	public static function register_editor_buttons($buttons)
	{
	    //register buttons with their id.
	    array_push($buttons, "payapi_button");
	    return $buttons;
	}


}
