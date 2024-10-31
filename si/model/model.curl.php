<?php
/*
*  PayApi signin curl
*  Author: florin
*  Version: 1.0
*  Updated: 20171129
*  License: GPL3
*/
class payapi_curl
{

	private   $buffer                =    null;
	private   $response              =   false;
	private   $error                 =   false;

	//->
	public function __construct($url, $data, $api_key, $timeout = 1)
	{
		global $payapi_debug;
		$payapi_debug->add('curling: ' . $url);
		$options = array(
            CURLOPT_URL              => $url,
            CURLOPT_RETURNTRANSFER   => 1,
            CURLOPT_HEADER           => 0,
            CURLOPT_SSL_VERIFYPEER   => 1,
            CURLOPT_FRESH_CONNECT    => 1,
            CURLOPT_FORBID_REUSE     => 1,
            CURLOPT_TIMEOUT          => $timeout,
            CURLOPT_HTTPHEADER       => array(
                'User-Agent: PayApi - WP curl'
            )
        );
        $this->buffer = curl_init();
        curl_setopt_array($this->buffer, $options);
        $curl_post = http_build_query(array("data" => $data));
        curl_setopt($this->buffer, CURLOPT_POSTFIELDS, $curl_post);
        $response = payapi_engine::unjsonize(curl_exec($this->buffer));
        $code = curl_getinfo($this->buffer, CURLINFO_HTTP_CODE);
        //-debug->$payapi_debug->add('response[' . $code . ']: ' . payapi_engine::jsonize($response));
        curl_close($this->buffer);
        if(is_int($code) === true && isset($response['code']) === true) {
	        if ($response['code'] === 200 && is_string($response['data']) === true) {
	        	$data = payapi_crypter::decode($response['data'], $api_key);
	        	$settings = payapi_engine::unjsonize($data);
	        	if (is_array($settings) === true) {
		        	$payapi_debug->add('success', 'login');
		        	return $this->set(200, $settings);
	        	} else {
	        		return $this->set(417);
	        	} 
	        } else {
	        	return $this->set($code);
	        }
	    }
	    return $this->set(600);
	}

	private function set($code, $response = false)
	{
		if ($response === false) {
			$this->response = payapi_response::get($code);
		} else {
			$this->response = payapi_response::get($code, $response);
		}
	}

	public function response()
	{
		return $this->response;
	}

	private function error($error = false)
	{
		if($error === false) {
			return $this->error;
		}
		$this->error[] = $error;
	}


}