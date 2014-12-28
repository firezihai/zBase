<?php
/**
 * PALM  Simple And Easy
 *
 * An open source application development framework for PHP 5.1.6 or newer
 * @author		    子海(zihaidetiandi@sina.com)
 * @copyright	  Copyright (c) 2014 - 2015, www.zihaidetiandi.com
 * @license		  http://www.apache.org/licenses/LICENSE-2.0
 * @link	      www.zihaidetiandi.com/zBase/
 */
/**
 *请求处理类
 *包含一个单一请求的请求数据和详细信息
 *
 * @author       子海(zihaidetiandi@sina.com)
 * @package      system.core
 * @since		 Version 1.0
 * @version      $Id hash.php 2014-11-28 8:52:00 $
 * @filesource
 */
class dispatcher{
	private function beforeDispatch($request){
		$this->parseParam($request);
	}
	/**
	 * 根据用户请求调用控制器和方法
	 * action为控制器类中的公共方法
	 */
	public function dispatch($request){
		$this->beforeDispatch($request);
		$controller = $this->getController($request);
		if (!($controller instanceof controller)){
			throw  new missingControllerException("ddd");
		}
		$controller->invokeAction($request);
	}
	
	public function getController($request){
			$controller = $this->loadController($request);
			if (!$controller){
				return false;
			}
			$reflection = new ReflectionClass($controller);
			if ($reflection->isAbstract() || $reflection->isInterface()){
				return false;
			}
			return $reflection->newInstance();
	}
	public function loadController($request){
		$controller = null;
		if (!empty($request->params['controller'])){
			$controller = $request->params['controller'];
		}
		if ($controller){
			$controller = $controller."Controller";
			if (class_exists($controller)){
				return $controller;
			}
		}
		return false;
	}
	public function parseParam($request){
		$url = $request->url();
		$param = router::parse($url);
		$request->addParam($param);
	}
}

?>