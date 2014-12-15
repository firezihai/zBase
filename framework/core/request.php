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
 class request{
 	/**
 	 * 当前url中的路径信息
 	 * <code>
 	 * http://www.example.com/index.php/index/test/index.php/?test=dd
 	 * return: /index/test/index.php/
 	 * </code>
 	 * @return string
 	 */
 	public static function pathInfo(){
 		$pathInfo = '';
 		if(isset($_SERVER['PATH_INFO'])){
 			$pathInfo = $_SERVER['PATH_INFO'];
 		}elseif (isset($_SERVER['ORIG_PATH_INFO'])){ //IIS CGI
 			$pathInfo = $_SERVER['ORIG_PATH_INFO'];
 			$scriptName = self::scriptName();
 			if(substr($scriptName, -1,1) != '/'){
 				$pathInfo = $pathInfo.'/';
 			}
 		}else{
 			$scriptName =self::scriptName();
 			$dirName = preg_replace('/[^\/]+$/', '', $scriptName);
 			$requestUri = self::requestUri();
 			$urlInfo = parse_url($requestUri);
 			if (strpos($urlInfo['path'],$scriptName) === 0){
 				$pathInfo = substr($urlInfo['path'],strlen($scriptName));
 			}elseif (strpos($urlInfo['path'],$dirName) === 0){
 				$pathInfo = substr($urlInfo['path'], strlen($dirName));
 			}
 		}
 		if ($pathInfo){
 			$pathInfo = '/'.ltrim($pathInfo,"/");
 		}
 		return $pathInfo;
 	}
 	/**
 	 * 服务器变量中的script_name信息
 	 * <code>
 	 * http://www.example.com/index.php/index/test/index.php/?test=dd
 	 * return: /index.php
 	 * </code>
 	 * @return string 
 	 */
 	public static function scriptName(){
 		return isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ($_SERVER['ORIG_SCRIPT_NAME'] ? $_SERVER['ORIG_SCRIPT_NAME'] : '');
 	}
 	/**
 	 * 当前url中，除主机头以外的url信息
 	 * <code>
 	 * http://www.example.com/index.php/index/test/index.php/?test=dd
 	 * return: /index.php/index/test/index.php/?test=dd
 	 * </code>
 	 * @return string
 	 */
 	public static function requestUri(){
 		if (isset($_SERVER['HTTP_X_REWRITE_URL'])){
 			return $_SERVER['HTTP_X_REWRITE_URL'];
 		}elseif (isset($_SERVER['REQUEST_URI'])){
 			return $_SERVER['REQUEST_URI'];
 		}elseif($_SERVER['ORIG_PATH_INFO']){
 			return $_SERVER['ORIG_PATH_INFO'].(!empty($_SERVER['QUERY_STRING'])? $_SERVER['QUERY_STRING'] : '');
 		}
 	}
 	/**
 	 * 获取当前脚本文件信息
 	 * @return string
 	 */
 	public static function scriptFilName(){
 		$filename = (isset($_SERVER['SCRIPT_FILENAME']) ? basename($_SERVER['SCRIPT_FILENAME']) : '');
 		if(!isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $filename){ 
 			$baseUrl = $_SERVER['SCRIPT_NAME'];
 		}elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename){
 			$baseUrl = $_SERVER['ORIG_SCRIPT_NAME'];
 		}elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $filename){
 			$baseUrl = $_SERVER['PHP_SELF'];
 		}elseif (isset($_SERVER['PHP_SELF']) && ($pos = strpos($_SERVER['PHP_SELF'], '/'.$filename) === false)){
 			$baseUrl = substr($_SERVER['PHP_SELF'], 0,$pos).'/'.$filename;
 		}elseif (isset($_SERVER['DOCUMENT_ROOT'])&& strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0 ){
 			$baseUrl = str_replace('\\','/',str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
 			$baseUrl{0} != '/'  && $baseUrl = '/'.$baseUrl;
 		}
 		return $baseUrl;
 	}
 	/**
 	 * 当前url信息
 	 * @return string
 	 */
	public static function url(){
		return self::scheme().'://'.self::host().self::requestUri();
	}
 	/**
 	 * 获取主机头
 	 * @return string 
 	 */
 	public static function host(){
 		$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
 		if (!empty($host)){
 			return $host;
 		}
 		$scheme = self::scheme();
 		$port = self::port();
 		$name = $_SERVER['SERVER_NAME'];

 		if (($scheme == 'http' && $port == 80) || ($scheme == 'https' && $port == 443)){
 			return $name;
 		}else{
 			return $name.':'.$port;
 		}
 	}
 	/**
 	 * 获取请求协议类型，即http或者https
 	 * @return string
 	 */
 	public static function scheme(){
 		return isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT']== '443' ? 'https' : 'http';
 	}
 	/**
 	 * 获取服务器端口
 	 * @return string
 	 */
 	public static function port(){
 		return $_SERVER['SERVER_PORT'];
 	}
 	/**
 	 * 获取客户端ip
 	 * @return string 如果ip格式不正确，而返回空
 	 */
 	public static function ip(){
 		if (isset($_SERVER['HTTP_CLINET_IP'])) {
 			$ip= $_SERVER['HTTP_CLINET_IP'];
 		}elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
 			$ip= $_SERVER['HTTP_X_FORWARDED_FOR'];
 		}elseif (isset($_SERVER['REMOTE_ADDR'])){
 			$ip= $_SERVER['REMOTE_ADDR'];
 		}
 		return preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $ip,$matchs)? $matchs[0] : '';
 	
 	}
	public static function data(){
		$data = array();
		$data['post'] = $_POST;
		$data['get'] = $_GET;
		$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
		if (isset($data[strtolower($method)])){
			return $data[strtolower($method)];
		}
	}
 }
?>