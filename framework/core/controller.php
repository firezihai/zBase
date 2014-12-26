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
class controller{
	public $methods;
	public function __construct(){

		$childMethods = get_class_methods($this);
		$parentMethods = get_class_methods('Controller');
		$this->methods = array_diff($childMethods, $parentMethods);
	}
	public function invokeAction(){
		try {
			$method = new ReflectionMethod($this,$request->params['action']);
			if ($this->isPrivateAction($method)){
				exit('私有方法');
			}
		}catch (ReflectionMethod $e){
			exit("action不存在");
		}
	}
	
	public function isPrivateAction(ReflectionMethod $method){
		$isPrivateAction = (!$method->isPublic() || !in_array($method->name, $this->methods));
		return $isPrivateAction;
	}
}
?>