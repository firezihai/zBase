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
 *
 * @author         子海(zihaidetiandi@sina.com)
 * @package      system.core
 *  @since		     Version 1.0
 * @version        $Id app.php 2014-11-21 00:52:00 $
 * @filesource
 */
class app{
    /**
     * 是否依赖php包含路径自动加载类文件,默认为依赖(true)。如果宿主环境不允许更改php包含路径，可以设置为false。
     * 或者你想使用自定义的加载器加载类文件。
     * @var boolean
     */
    public static $enableInclucePath = false;
    /**
     * 
     * @var array 
     */
    protected static  $package=array();
   
    private static $_imports = array();
    /**
     * include_path路径数组
     * @var array
     */
    private static $_includePath;
    private static $_packagePathMap = array();
    private static $_classMap = array();
    public function bulid($package=array()){
        //$systemPackage =  
    }
    
    public static function import($package,$forceInclude = false){
        if (self::$_imports[$package]){
            return self::$_imports[$package];
        }
        if (($packagePath =self::getPackagePath($package)) !== false){
            if (self::$_includePath === null){
                self::$_includePath = array_unique(explode(PATH_SEPARATOR, get_include_path()));
                if (($key = array_search('.', self::$_includePath,true)) !==false){
                    unset(self::$_includePath[$key]);
                }
            }
            array_unshift(self::$_includePath,$packagePath);

            if (self::$enableInclucePath && set_include_path('.'.PATH_SEPARATOR.implode(PATH_SEPARATOR,$self::$_includePath)) === false){
                self::$enableInclucePath = false;
            }
    
            return self::$_imports[$package];
        }
    }
    
    public static function getPackagePath($package){
        if(isset(self::$_packagePathMap[$package])){
            return self::$_packagePathMap[$package];
        }else{
           if (!isset(self::$package[$package])){
               return false;
           }
          return  self::$_packagePathMap[$package] = APP_DIR.DS.str_replace('.', DS, $package);
        }
    }
    
    /**
     * 类加载器
     */
    public static function autoloader($className){
        if (isset(self::$_classMap[$className])){
            return ;
        }else{
            if (self::$enableInclucePath === false){
                foreach (self::$_includePath as $path){
                    $classFile = $path.DS.$className.'.php';
                    if (is_file($classFile)){
                        self::$_classMap[$className] = $classFile;
                        include $classFile;
                        if (basename(realpath($classFile)) !== $className.'.php'){
                            exit('Class name '.$className.' does not match class file "'.$classFile.'"');
                            break;
                        }
                    }
                }
            }else{
                include $className.'.php';
                self::$_classMap[$className] = $className;
            }
        }
    }
     public static function uses($package){
         self::$package[$package] = $package;
     }
}
?>