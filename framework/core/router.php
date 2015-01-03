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
		self::$defaultController = hash::get(self::$_router, self::$defaultApp.'.defaultController');
		self::$defaultAction = hash::get(self::$_router, self::$defaultApp.'.defaultAction');
		$var = array();
		if ((strpos($url, '?')) !== false){
			list($url, $queryParameters) = explode('?', $url, 2);
			parse_str($queryParameters, $var);
		}elseif (strpos($url, '/') !== false){
			if($url{0} == '/') $url = substr($url, 1);
			$path = explode('/', $url);
			$pathInfo = explode('/',request::pathInfo());
			$path = array_merge($path,(array)$pathInfo);
		}
		if (isset($var['a'])){
			$var['action'] = $var['a'];
			unset($var['a']);
		}
		if (isset($var['c'])){
			$var['controller'] = $var['c'];
			unset($var['c']);
		}
		if (isset($path)){
			$var['action'] = array_pop($path);
			if (!empty($path) &&!empty($path)){
				$var['controller'] = array_pop($path);
			}
			if (!empty($path)){
				$var['app'] = array_pop($path);
			}
		}
		print_r($var);
		if (!isset($var['app']) || empty($var['app'])){
			$var['app'] = self::$defaultApp;
		}
		if (!isset($var['controller']) || empty($var['controller'])){
			$var['controller'] = self::$defaultController ;
		}
		if (!isset($var['action']) || empty($var['action'])){
			$var['action'] = self::$defaultAction ;
		}

		self::filterRouter($var);
		return $var;
	}
	public static function getRouter($path){
		return hash::get(self::$_router, $path);
	}
	/**
	 * 
	 * @param array $param 路由数组
	 * @throws baseException
	 * @return array 路由数组
	 */
	public static function filterRouter(&$param){
		$actionStatus = hash::get(self::$_router, $param['app'].'.'.$param['controller'].'Controller.'.$param['action']);
		echo $param['app'].'.'.$param['controller'].'Controller.'.$param['action'];
		if ( $actionStatus === false){
			if ($param['action'] == self::$defaultAction && $param['controller'] == self::$defaultController && $param['app'] == self::$defaultApp){
				throw new baseException('Methods the default controller default application has been disabled, please open the routing configuration file');
			}elseif ($param['action'] == self::$defaultAction && $param['controller'] == self::$defaultController){
				$param['app'] = self::$defaultApp;
			}elseif($param['action'] == self::$defaultAction){
				$param['app'] = self::$defaultController;
			}
		}elseif (hash::get(self::$_router, $param['app'].'.'.$param['controller'].'Controller.disable') && $actionStatus === false){
			if ($param['controller'] == self::$defaultController && $param['app'] == self::$defaultApp){
				throw new baseException('The Controller of the default application has been disabled, please open the routing configuration file');
			}elseif ($param['controller'] == self::$defaultController){
				$param['app'] = self::$defaultApp;
			}
		}elseif (hash::get(self::$_router, $param['app'].'.disable')){
			throw new baseException('The default application has been disabled, please open the routing configuration file');
		}
		return $param;
	}
	
}

?>