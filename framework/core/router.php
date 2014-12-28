<?php
/**
 * PALM  Simple And Easy
 *
 * An open source application development framework for PHP 5.1.6 or newer
 * @package         base
 * @copyright       Copyright (c) 2014  zihai  All rights reserved.
 * @author		    子海(zihaidetiandi@sina.com)
 * @copyright	    Copyright (c) 2014 - 2015, www.zihaidetiandi.com
 * @license		     http://www.apache.org/licenses/LICENSE-2.0
 * @link	            	www.zihaidetiandi.com/palm/
 * @since		        Version 1.0
 * @filesource
 */
class router{
	public static function parse($url){
		$var = array();
		if ((strpos($url, '?')) !== false){
			list($url, $queryParameters) = explode('?', $url, 2);
			parse_str($queryParameters, $var);
		}elseif (strpos($url, '/')){
			$path = explode('/', $url);
		}else{
			parse_str($url,$var);
		}
		if (isset($var['a'])){
			$var['action'] = $var['a'];
			unset($var['a']);
		}
		if (isset($var['c'])){
			$var['controller'] = $var['c'];
			unset($var['c']);
		}
		if (isset($var['m'])){
			$var['module'] = $var['m'];
			unset($var['m']);
		}
		if (isset($path)){
			$var['action'] = array_pop($path);
			if (!empty($path) &&!empty($path)){
				$var['controller'] = array_pop($path);
			}
			if (!empty($path)){
				$var['module'] = array_pop($path);
			}
		}
		return $var;
	}
}
?>