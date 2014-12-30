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
class string{
	/**
	 * 字符转义方法
	 * @param array|string $value 待转义的数组或字符串
	 * @return array|string 返回经过stripslashes转义后的$value
	 */
	public static  function newStripslashes($value){
	/*	if(!is_array($value)) return stripslashes($value);
		foreach($value as $key=>$v) $value[$key] = $this->newStripslashes($v);
		return $value;*/
		$value = is_array($value)? array_map(array('this','newStripslashes'), $value) : stripslashes($value);
		return $value;
	}
}
?>