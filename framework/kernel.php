<?php
/**
 * PALM  Simple And Easy
 *
 * An open source application development framework for PHP 5.1.6 or newer
 * @author		    子海(zihaidetiandi@sina.com)
 * @copyright	    Copyright (c) 2014 - 2015, www.zihaidetiandi.com
 * @license		     http://www.apache.org/licenses/LICENSE-2.0
 * @link	            	www.zihaidetiandi.com/palm/
 */
/**
 * zBase核心类库目录名
 */
 defined('ZBASE_DIR') or define('ZBASE_DIR',  dirname(__FILE__));
 /**
  * 目录分格符
  */
define('DS', DIRECTORY_SEPARATOR);
/**
 *助手类，它服务于整个框架
 * @package		 system.core
 * @author		 子海(zihaidetiandi@sina.com)
 * @since		 version 1.0
 * @filesource
 */
 class kernel{
     /**
      * kernel单例方法的类实列数组。
      * 键是一个类名或者一个序列化字符串，数组的值是一个类的实例化对象
      * @var array 
      */
     public static $_singleInstanceMap = array();
	/**
	 * 引导应用程序方法
	 *
	 * @param array $config  应用程序配置数组  
	 */
     public static function boot($config){
        include ZBASE_DIR.DS.'core'.DS.'app.php';
        include ZBASE_DIR.DS.'core'.DS.'exception.php';
        self::systemPackage();
		kernel::single('app')->init($config);
        if(!self::registerAutoloader()){
            function __autoload($className){
                app::autoload($className);
            }
        }
        configure::write('main',$config);
        $dispatcher = new dispatcher();
        $dispatcher->dispatch(new request());

     }
     /**
      * 单例实法，用来实例化一个类，并返回这个类的对象。
      * 
      *  single方法将类的实例对象储存在kernel::$_singleInstanceMap私有属性中。在第二次调用时，
      *  直接将kernel::$_singleInstanceMap数组中匹配的对象返回。
      *  同一个类，传递的参数不同， 将返回不同的类实例。
      * @param string $className
      * @param mixed $arg
      * @return mixed 返回一个类的实例
      */
     public static function single($className,$arg=null){
         if(is_object($arg)){
             $key = get_class($arg);
             $key = '__class__'.$key;
         }elseif ($arg == null){
             $key = md5('__arg__'.$className);
         }else{
             $key = md5('__arg__'.$className.serialize($arg));
         }
         if(!isset(self::$_singleInstanceMap[$className][$key])){
             self::$_singleInstanceMap[$className][$key] = $arg == null ? new $className(): new $className($arg);
         }
          return    self::$_singleInstanceMap[$className][$key];
     }
     /**
      * 注册一个自定义类加载器
      * 
      * @param array $load
      * @return boolean
      */
     public static function registerAutoloader($load=array('app','autoloader')){
         if(function_exists('spl_autoload_register')){
             return spl_autoload_register($load);
         }
         return false;
     }
     /**
      * 导入框架系统类包
      * @return void
      */
     private static  function systemPackage(){
     	$systemPackage = array('system.core',
     			               'system.model',     			
     	                       'system.model.driver');
     	app::uses('system', ZBASE_DIR);
     	foreach ($systemPackage as $package){
     		app::uses($package);
     	}
     }
     /**
      * 格式化字符串
      * <code>
      * kernel::t("controller class {controller} not found ",array("{controller}"=>$controller));
      * kernel::t("controller class {controller} not found ",$controller);
      * </code>
      * @param string $msg
      * @param array|string $params
      * @return string
      */
     public static function t($msg,$params=array()){
     	if (is_string($params)){
     		$params = array($params);
     	}
     	if (isset($params[0])){
     		$params['{'.$params[0].'}'] = $params[0];
     		unset($params[0]);
     	}
     	return $params !== array() ? vsprintf($msg,$params) : $msg;
     }
 }
?>