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
 * palm核心类库目录名
 */
 defined('ZBASE_DIR') or define('ZBASE_DIR',  dirname(__FILE__));
 /**
  * 数据目录名
  */
 /**
  * 目录分格符
  */
define('DS', DIRECTORY_SEPARATOR);
/**
 *助手类，它服务于整个框架
 * @package		system.core
 * @author		子海(zihaidetiandi@sina.com)
 * @since		     version 1.0
 * @filesource
 */

 class kernel{
    /**
     *  框架自动加载类的类图数组。
     *  这个数组的键是一个类名，数组的值则是对应的类文件路径
     * @var array
     */
     public static $_classMap = array();
     
     /**
      * kernel单例方法的类实列数组。
      * 键是一个类名或者一个序列化字符串，数组的值是一个类的实例化对象
      * @var array 
      */
     public static $_singleInstanceMap = array();
     
     /**
      * 储存导入类文件。
      * 这个数组的键是一个类名，数组的值则是对应的类文件路径
      * @var array
      */
     private static $_import = array();
     
     
     /**
      * 初始化应用程序。
      * 
      */
     public function boot($config){
         include ZBASE_DIR.DS.'core'.DS.'app.php';
		kernel::single('app')->init($config);
         if(!self::registerAutoloader()){
             function __autoload($className){
                 app::autoload($className);
             }
         }
         
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
 }
?>