<?php
/*
*  PayApi debugger
*  Author: florin
*  Version: 1.0
*  Updated: 20171122
*  License: GPL3
*/
final class payapi_debugger
{

	private   $buffer    = array();
	private   $file      = false;
	private   $microtime = false;
	private   $enabled   = false;

	public function __construct($enable = false)
	{
		if($enable === true) {
			$this->enabled = true;
		}
		$this->microtime = microtime(true);
		$this->file = payapi_router::debug();
		$this->reset();
		$this->blank();
		$this->writte('== payapi debugger == ' . payapi_engine::timestamp() . ' ==>');
	}

	public function add($info, $label = 'info')
	{
		$entry = $this->serialize($info, $label);
		$this->writte($entry);
	}

	private function writte($entry)
	{
		if ($this->enabled === true) {
			$filtered = filter_var($entry, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$this->buffer($filtered);
	        return error_log($filtered . "\n", 3, $this->file);
		}
		return true;
	}

	private function buffer($entry)
	{
		$this->buffer[] = $entry;
	}

	private function serialize($info, $label)
	{
		if (is_string($info) === true) {
			$stringify = $info;
		} else {
			$stringify = '(array)' . payapi_engine::jsonize($info);
		}
		$miliseconds = str_pad(round((microtime(true) - $this->microtime) * 1000, 0), 4, '0', STR_PAD_LEFT);
		return $miliseconds . ' [' . $label . '] ' . $stringify;
	}

	private function blank()
    {
        $this->writte('');
    }

    private function reset()
    {
        $this->buffer = false;
        file_put_contents($this->file, '');
        return $this->blank();
    }

    private function resume()
    {
    	$microtime = microtime(true);
    	$process = round(($microtime - $this->microtime), 4);
    	$this->writte('<== execution == ' . $process . 'ms. ==');
    }


	public function __destruct()
	{
		//-> @FIXME not called
		$this->resume();
	}

	public function __toString()
	{
		return payapi_engine::jsonize(array(
			"file"   => $this->file,
			"buffer" => $this->buffer
		));
	}


}