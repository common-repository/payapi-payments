<?php
/*
*  PayApi request
*  Author: florin
*  Version: 1.0
*  Updated: 20171122
*  License: GPL3
*/
final class payapi_request
{

	private $get = array();
	private $post = array();

	public function __construct()
	{
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
	}

	public function get()
	{
		if (isset($this->get[$key]) === true) {
			return $this->get[$key];
		}
		return null;
	}

	public function post()
	{
		if (isset($this->post[$key]) === true) {
			return $this->post[$key];
		}
		return null;
	}

	private function clean($data)
	{
        if(is_array($data) === true) {
            $clean = array();
            foreach ($data as $key => $value) {
                $clean[$this->clean($key)] = $this->clean($value);
            }
            return $clean;
        } else if (is_string($data) === true) {
            return addslashes($data);
        }
        //->
        return null;
	}


}