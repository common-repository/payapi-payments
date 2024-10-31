<?php
/*
*  PayApi settings
*  Author: florin
*  Version: 1.0
*  Updated: 20171122
*  License: GPL3
*
* @NOTE adds prefix to settings
*/
final class payapi_settings
{
	private   	$prefix = 'payapi';
	private   	$keys = array(
					"public_id",
					"api_key",
					"mode",
				);

	private function prefix($key)
	{
		return $this->prefix . '_' . $key;
	}
	public function option($key)
	{
		return get_option($key);
	}

	public function get($key)
	{
		return  get_option($this->prefix($key));
	}

	public function set($key, $value)
	{
		return add_option($this->prefix($key), $value);
	}

	public function delete($key)
	{
		return delete_option($this->prefix($key));
	}

	public function reset()
	{
		$public_id = $this->get('public_id');
		if (is_string($public_id) === true) {
			foreach ($this->keys as $key => $value) {
				$this->delete($value);
			}
		}
	}	

	public function update($key, $value)
	{
		return update_option($this->prefix($key), $value);
	}


}