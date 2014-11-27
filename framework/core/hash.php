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
 *处理和获取数组中数据的方法集合
 *hash类使用'.'标记构造的键操作链设置和提取数组的值，这种方法更加方便和直观了解当前操作的数组结构。
 * @author 子海(zihaidetiandi@sina.com)
 * @package base.core
 *  @since		        Version 1.0
 * @version        $Id hash.php 2014-11-21 00:52:00 $
 * @filesource
 */
class hash{
    /**
     * 根据$path提供的键操作链，从 $data数组中获取相应的值
     * 通过点标记构造的键操作链，能够快速的获取数组的值
     * <code>
     * hash::get(array('app'=>array('core'=>array('app.php','configure.php'))),'app.core'); //return array('app.php','configure.php')
     * </code>
     * 
     * @param array $data
     * @param string $path
     * @return mixed 如果数组中存在此键操作链，返回键操作链对应的值，否则返回null
     */
   public static function get(array $data,$path){
       if(empty($data)){
           return null;
       }
       if (is_string($path) || is_numeric($path)) {
           $parts = explode('.', $path);
       } else {
           $parts = $path;
       }
       foreach ($parts as $key) {
           if (is_array($data) && isset($data[$key])) {
               $data =& $data[$key];
           } else {
               return null;
           }
       }
       return $data;
   }
   /**
    * 按照$path向$data数组中插入$value
    * 你可以向$data数组中插入一个字符串，也可以向数组中插入一个数组
    * <code>
    * $data = array('app'=>array('core'=>array('app.php','configure.php')));
    * hash::insert($data,'app.other',array('hash.php','component.php'));
    * </code>
    * @param array $data 执行插入操作的数组
    * @param string $path 插入的值对应的键操作链
    * @param string | array $value  要插入的值 
    * @return array 返回执行插入操作后的$data
    */
   public static function insert(array $data,$path,$value=null){
       $list = &$data;
       $pieces = explode('.', $path);
       $count = count($pieces);
       $last = $count - 1;
       foreach ($pieces as $i=> $key ){
           if(is_numeric($key) && intval($key) || $key === '0'){
               $key= intval($key);
           }
           if ($i == $last){
               $list[$key] = $value;
               return $data;
           }
           if (!isset($list[$key])){
               $list[$key] = array();
           }
           $list = &$list[$key];
           if (!is_array($list)){
               $list = array();
           }
       }
   }
}
?>