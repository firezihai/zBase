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
	public static $defaultApp;
	public static $defaultController;
	public static $defaultAction;
	public static $appDefaultCtl = '';
	public static $appDefaultAct = '';
	public static $_initialized = false;
	public static $_router=array();
	public static function loadRouter(){
		self::$_initialized = true;
		self::$_router = configure::load('router','',false);
	}
	public static function reload(){
		self::loadRouter();
	}
	public function disable($value,$controller,$action = '',$app=''){
		if (empty($app) && hash::get($this->_router, 'defaultApp')){
			$app = $this->_router['defaultApp'];
		}else{
			throw new baseException(kernel::t('Default app not set'));
		}
		$path = !empty($action) ? $app.'.'.$controller.'.'.$action : $app.'.'.$controller;
		hash::insert($this->_router, $path,$value);
	}
	public static function parse($url){
		if (!self::$_initialized){
			self::loadRouter();
		}
		self::$defaultApp = hash::get(self::$_router, 'defaultApp');
		self::$defaultController = hash::get(self::$_router, 'defaultController');
		self::$defaultAction = hash::get(self::$_router, 'defaultAction');
		$var = array();
		if ((strpos($url, '?')) !== false){
			list($url, $queryParameters) = explode('?', $url, 2);
			parse_str($queryParameters, $var);
		}elseif (strpos($url, '/') !== false){
			//if($url{0} == '/') $url = substr($url,1);
			$url = trim($url,'/');
			$path = explode('/', $url);
			if ($pathInfo = request::pathInfo()){
				$pathInfo = explode('/',$pathInfo);
				$path = array_merge($path,$pathInfo);
			}
			$var['action'] = array_pop($path);
			if (!empty($path)){
				$var['controller'] = array_pop($path);
			}
			if (!empty($path) && configure::read('base.multipleApp')){
				$var['app'] = array_pop($path);
			}
		}
		if (isset($var['a'])){
			$var['action'] = $var['a'];
			unset($var['a']);
		}
		if (isset($var['c'])){
			$var['controller'] = $var['c'];
			unset($var['c']);
		}
		if (!isset($var['app']) || empty($var['app'])){
			$var['app'] = self::$defaultApp;
		}
		$var['app'] = self::getAppReal($var['app']);
		self::$appDefaultCtl = hash::get(self::$_router, $var['app'].'.defaultController');
		self::$appDefaultAct = hash::get(self::$_router, $var['app'].'.defaultAction');
		if (!isset($var['controller']) || empty($var['controller'])){
			$var['controller'] = self::$appDefaultCtl ? self::$appDefaultCtl : self::$defaultController;
		}
		if (!isset($var['action']) || empty($var['action'])){
			$var['action'] = self::$appDefaultAct ? self::$appDefaultAct : self::$defaultAction ;
		}
		self::filterRouter($var);
		return $var;
	}
	public static function getRouter($path){
		return hash::get(self::$_router, $path);
	}
	public static function getAppReal($app){
		$real = hash::get(self::$_router, 'alias.'.$app);
		if ($real){
			$app = $real;
		}
		return $app;
	}
	/**
	 * 
	 * @param array $param 路由数组
	 * @throws baseException
	 * @return array 路由数组
	 */
	public static function filterRouter(&$param){
		$actionStatus = hash::get(self::$_router, $param['app'].'.'.$param['controller'].'Controller.'.$param['action']);
		if ($param['app'] == self::$defaultApp){ //如果是默认应用
			if($actionStatus === false){ //方法
				if ($param['action'] == self::$defaultAction  && $param['controller'] == self::$defaultController){
					throw new baseException('Methods the default controller default application has been disabled, please open the routing configuration file');
				}elseif($param['action'] == self::$defaultAction){
					$param['controller'] = self::$defaultController;
					$param['action'] = self::$defaultAction;
				}else{
					$param['action'] = self::$defaultAction;
				}
			}elseif (hash::get(self::$_router, $param['app'].'.'.$param['controller'].'Controller.disable')){ //控制器
				if ($param['controller'] == self::$defaultController){
					throw new baseException('The Controller of the default application has been disabled, please open the routing configuration file');
				}else{
					$param['controller'] = self::$defaultController;
				}
			}elseif (hash::get(self::$_router, $param['app'].'.disable')){//应用
				throw new baseException('The default application has been disabled, please open the routing configuration file');
			}
		}else{
			if($actionStatus === false){
				if ($param['action'] == self::$appDefaultAct && $param['controller'] == self::$appDefaultCtl){
					$param['app'] = self::$defaultApp;
					$param['controller'] = self::$defaultController;
					$param['action'] = self::$defaultAction;
				}elseif($param['action'] == self::$appDefaultAct){
					$param['controller'] = self::$appDefaultCtl;
					$param['action'] = self::$appDefaultAct;
				}else{
					$param['action'] = self::$appDefaultAct;
				}
			}elseif (hash::get(self::$_router, $param['app'].'.'.$param['controller'].'Controller.disable')){
				if ($param['controller'] == self::$appDefaultCtl){
					throw new baseException('The Controller of the default application has been disabled, please open the routing configuration file');
				}else{
					$param['app'] = self::$defaultApp;
					$param['controller'] = self::$defaultController;
					$param['action'] = self::$defaultAction;
				}
			}elseif (hash::get(self::$_router, $param['app'].'.disable')){
				throw new baseException('The default application has been disabled, please open the routing configuration file');
			}
		}
	}
	
}

?>