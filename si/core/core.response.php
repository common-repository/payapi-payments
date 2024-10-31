<?php
/*
*  PayApi response
*  Author: florin
*  Version: 1.0
*  Updated: 20171122
*  License: GPL3
*/
final class payapi_response
{

    private   
       static 	$responses                       =    array(
					// @NOTE PHP ZEND INTERNAL STATUS HEADERS

					// Informational 1xx
					100 =>                        'continue',
					101 =>             'switching Protocols',

					// Success 2xx
					200 =>                         'success',
					201 =>                         'created',
					202 =>                        'accepted',
					203 =>   'non-Authoritative Information',
					204 =>                      'no Content',
					205 =>                   'reset Content',
					206 =>                 'partial Content',

					// Redirection 3xx
					300 =>                'multiple choices',
					301 =>               'moved permanently',
					302 =>                           'found',  // 1.1
					303 =>                       'see Other',
					304 =>                    'not modified',
					305 =>                       'use proxy',
					// 306 is deprecated but reserved
					307 =>              'temporary redirect',

					// Client Error 4xx
					400 =>                     'bad request',
					401 =>                    'unauthorized',
					402 =>                'payment required',
					403 =>                       'forbidden',
					404 =>                       'not found',
					405 =>              'method not allowed',
					406 =>                  'not acceptable',
					407 =>   'proxy authentication required',
					408 =>                 'request timeout',
					409 =>                        'conflict',
					410 =>                            'gone',
					411 =>                 'length required',
					412 =>             'precondition failed',
					413 =>        'request entity too large',
					414 =>            'request-uri too long',
					415 =>          'unsupported media type',
					416 => 'requested range not satisfiable',
					417 =>              'expectation failed',

					// Server Error 5xx
					500 =>           'internal server error',
					501 =>                 'not implemented',
					502 =>                     'bad gateway',
					503 =>             'service unavailable',
					504 =>                 'gateway timeout',
					505 =>      'http version not supported',
					509 =>        'bandwidth limit exceeded',

					// @NOTE Extra One(s) 6xx  :)
					600 =>                         'boo boo'
				);


    public static function get($key, $info = false)
    {
    	if (is_int($key) === true && isset(self::$responses[$key]) === true) {
    		$code = $key;
    	} else {
    		$code = 600;
    	}
    	if ($info !== false) {
    		$data = $info;
    	} else {
    		$data = self::$responses[$code];	
    	}
    	if(is_string($data) === true) {
    		$stringify = $data;
    	} else {
    		$stringify = payapi_engine::jsonize($data);
    	}
    	
    	return array(
    		"code" => (int) $code,
    		"data" => (string) $stringify
    	);
    }


}