<?php
/*
*  PayApi engine
*  Author: florin
*  Version: 1.0
*  Updated: 20171129
*  License: GPL3
*/
final class payapi_engine
{

    private 
       static   $version  = '1.0.0';
    private     $data     = array();
	private     $api      = 'input.payapi.io';
    private     $staging  = 1;
    private     $crypter  = false;
    private     $router   = false;

	public static function init()
	{
        self::api();
	}

	public function has($key)
	{
		if (is_string($key) === true && isset($this->data[$key]) === true) {
			return true;
		}
		return false;
	}

	public function get($key = false)
	{
		if($key === false) {
			return $this->data;
		} else if ($this->has($key) === true) {
			return $this->data['key'];
		}
		return false;
	}

	public function set($key, $value = false)
	{
		$this->data[$key] = $value;
		return $this->data[$key];
	}

	public function end_point($staging = false)
	{
		if ($staging === true) {
			return 'staging' . '-' . $this->api;
		}
		return $this->api;
	}

    public static function plugin_activation()
    {
        return self::install();
    }

    private static function install()
    {
        $crypter_model = __DIR__ . DIRECTORY_SEPARATOR . 'core' . '.' . 'crypter' . '.' . 'php';
        $outdated = file_get_contents($crypter_model);
        $obsolete = 'replace_encription_key_jff6yGSd6FhH';
        if (strpos($outdated, '= "' . $obsolete . '"') !== false) {
            $updated = str_replace('= "' . $obsolete . '"', '= "' . self::randomKey() . '"', $outdated);
            return file_put_contents($crypter_model, $updated);
        }
        return true;
    }

    private static function randomKey($length = 30)
    {
        $key = "";
        $alphabet = '@_-!¡?@#&%()=0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        for ($i=0; $i < $length; $i++) {
            $key .= $alphabet[rand(0, strlen($alphabet) - 1)];
        }
        return 'payapi_' . $key;
    }

    public static function plugin_deactivation()
    {
        global $payapi_debug, $payapi_settings;
        $payapi_debug->add('[plugin] unistall');
        $payapi_settings->reset();
        return true;
    }

    public static function version()
    {
        return self::$version;
    }

    public static function payapi_get_merchant_settings($public_id, $api_key, $mode) {
        global $payapi_settings, $payapi_debug;
        $end_point = payapi_router::settings($public_id, $mode);
        $payload = payapi_crypter::encode(array(
            "storeDomain" => $payapi_settings->option('siteurl')
        ), $api_key);
        payapi_loader::model('curl');
        $request = new payapi_curl($end_point, $payload, $api_key);
        $response = $request->response();
        //-debug->$payapi_debug->add($response, 'settings');
        return $response;
    }

    public static function view($name, array $args = array())
    {
        global $payapi_settings;
        $public_id = $payapi_settings->get('public_id');
        if (is_string($public_id) !== true) {
            $active = false;    
        } else {
            $active = true;
        }
        $info = array_merge(
            array(
                "version"         => self::versionize(),
                "media"           => payapi_router::ui(true),
                "brand"           => payapi_branding::get(),
                "option_selected" => ' selected="selected"',
                "public_id"       => $payapi_settings->get('public_id'),
                "token"           => payapi_crypter::token(),
                "active"          => $active,
                "mode"            => $payapi_settings->get('mode')
            ),
            $args
        );
        //-> isolates variables
        $args['payapi'] = apply_filters('payapi_payments_view_arguments', $info, $name);
        foreach ($args as $key => $val) {
            $$key = $val;
        }
        load_plugin_textdomain('payapi_payments');
        $file = payapi_router::ui() . 'view/'. $name . '.php';
        echo '<div class="payapi">';
        include($file);
        echo '</div>';
    }

    public static function buttom(array $args = array())
    {
        $args = apply_filters('payapi_payments_view_arguments', $args, $name);
        foreach ($args as $key => $val) {
            $$key = $val;
        }
        load_plugin_textdomain('payapi_payments');
        $file = payapi_router::ui() . 'view/form.payment.php';
        ob_start();
        require($file);
        $buttom = ob_get_clean();
        return $buttom;
    }

    private static function versionize()
    {
        return '<span class="smaller">v</span>' . str_replace('.0', '<span class="smaller">.0</span>', self::version());
    }

    public static function timestamp()
    {
        return date('Y-m-d H:i:s e', time());
    }

    public static function jsonize($data)
    {
        //->
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function unjsonize($json)
    {
        //->
        return json_decode($json, true);
    }

    public function undefined()
    {
          return '___undefined___';
    }
    //-> payapi_signin_end_point
    private static function api()
    {
        add_action( "rest_api_init", function () {
            payapi_loader::controller('signin');
            register_rest_route( "payapi/v1", "signin", array(
                "methods" => 'POST',
                "callback" => array('payapi_controller_signin', 'login'),
                'args' => array(
                    'public_id' => array(
                        'required' => true,
                        'type' => 'string',
                        'description' => __('Payapi Merchant public ID', 'payapi_payments'),
                    ),
                    'api_key' => array(
                        'required' => true,
                        'type' => 'string',
                        'description' => __('Payapi Merchant API key', 'payapi_payments'),
                    ),
                    'mode' => array(
                        'required' => true,
                        'type' => 'int',
                        'description' => __('Payapi Payments mode', 'payapi_payments'),
                    ),
                    'token' => array(
                        'required' => true,
                        'type' => 'string',
                        'description' => __('Payapi security token', 'payapi_payments'),
                    )
                )
            ));
        });
    }


}