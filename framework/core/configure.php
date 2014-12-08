<?php
/**
 * PALM  Simple And Easy
 *
 * An open source application development framework for PHP 5.1.6 or newer
 * @author		    子海(zihaidetiandi@sina.com)
 * @copyright	 Copyright (c) 2014 - 2015, www.zihaidetiandi.com
 * @license		 http://www.apache.org/licenses/LICENSE-2.0
 * @link	     www.zihaidetiandi.com/zBase/
 */
/**
 * 
 * @author       子海(zihaidetiandi@sina.com)
 * @package      system.core
 * @since		 Version 1.0
 * @version      $Id configure.php 2014-11-26 09:52:00 $
 * @filesource
 */
class configure{
    /**
     * 数组储存的是当前配置信息
     * 
     * @var array
     */
    protected static $conf = array('debug'=>0);
    /**
     * 向app::$conf 插入一个动态变量配置信息
     *
     *注意：如果要插入的变量在app::$conf已经存在，将会覆盖原来的值。
	 * <code>
	 *  app::write('app.key1','向app::$conf中键名为app的值里，插入一条数键名为key1的数据')
	 *  app::write(array('app.key1'=>'向app::$conf中键名为app的值里，插入一条数键名为key1的数据'))
	 *  app:write('app',array('key1'=>'向app::$conf中键名为app的值里，插入一条数键名为key1的数据',
	 *                              'key2'=>'向app::$conf中键名为app的值里，插入一条数键名为key1的数据'
	 *  ))
	 *  app:write(array('app.key1'=>'向app::$conf中键名为app的值里，插入一条数键名为key1的数据',
	 *                              'app.key2'=>'向app::$conf中键名为app的值里，插入一条数键名为key1的数据'
	 *  ))
	 * </code>
     * @param string | array  $config 要插入的变量，变量可以使用点符号表示层级
     * @param mixed $value 变量的值
     * @return boolean 如果插入成功将返回true 
     */
    public static function write($config,$value=null){
        if(!is_array($config)){
            $config = array($config=>$value);
        }
        foreach($config as $key=>$v){
            self::$conf = hash::insert(self::$conf, $key,$v);
        }
        return true;
    }
    /**
     * 用于读取当前app::$conf的配置信息
     * 
     * @param string $var 要获取的变量,可以使用'.'点标记获取变量单元
     * @return mixed 如果$conf存在此变量，返回对应的值，否则返回null
     */
    public static function read($var = null){
        if($var === null){
           return self::$conf;
        }
        return hash::get(self::$conf, $var);
    }
    
    public function merge($data,$merge){
    	
    }
    public static function load($file,$key= ''){
    	if (strpos($key, '..') !== false){
    		exit('Cannot load configuration files with ../ in them');
    	}
    	$file = str_replace('.', '/', $key);
    	$file = APP_ROOT.DS.'config'.DS.$file.'.php';
    	if (!empty($key)){
    		$value[$key] = include $file;
    	}else{
    		$value = include $file;
    	}
    	return self::write($value);
    }
 	public static function uses($file,$key){
 		if (self::load($file)){
 			return self::read($key);
 		}
 	}
}
?>