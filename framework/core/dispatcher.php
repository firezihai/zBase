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
	/**
	 * 调用调度方法之前的回调方法 
	 * @param request $request
	 */
	protected  function beforeDispatch($request){
		$this->parseParam($request);
	}
	/**
	 * 调用调度方法之后的回调方法
	 * @param request $request
	 */
	protected function afterDispatch($request){}
	/**
	 * 应用程序的调度方法。
	 * 根据当前请求的url信息，调用相应的控制器和方法。
	 * 其中的action为控制器的公共方法。如果控制器不是controller的子类，
	 * 将抛出异常信息
	 * @param request $request
	 * @throws missingControllerException
	 * return void
	 */
	public function dispatch($request){
		$this->beforeDispatch($request);
		$controller = $this->getController($request);
		if (!($controller instanceof controller)){
			throw  new missingControllerException("ddd");
		}
		$controller->invokeAction($request);
		$this->afterDispatch($request);
	}
	/**
	 * 获取当前控制器对象实例
	 * @param request $request
	 * @return boolean|object
	 */
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
	/**
	 * 加载控制器
	 * @param request $request
	 * @return string|boolean
	 */
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
	/**
	 * 解析当前请求的url，并转换成一个参数数组
	 * @param request  $request
	 */
	public function parseParam($request){
		$url = $request->url();
		$param = router::parse($url);
		$request->addParam($param);
	}
}

?>