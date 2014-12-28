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
class baseException extends RuntimeException{
	protected  $messageTemplate ='';
	public $attribute = '';
	public function __construct($message,$code=500){
		$message = kernel::t($this->messageTemplate,$message);
		parent::__construct($message, $code);
	}
}
class missingControllerException extends  baseException{
	public $messageTemplate = 'Controller class {controller} could not be found.';
	public function __construct($message,$code=404){
		parent::__construct($message,$code);
	}
}
?>