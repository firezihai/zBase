<?php
/**
 * PALM  Simple And Easy
 *
 * An open source application development framework for PHP 5.1.6 or newer
 * @author		    子海(zihaidetiandi@sina.com)
 * @copyright	 Copyright (c) 2014 - 2015, www.zihaidetiandi.com
 * @license		 http://www.apache.org/licenses/LICENSE-2.0
 * @link	     www.zihaidetiandi.com/palm/
 */
/**
 *
 * @author       子海(zihaidetiandi@sina.com)
 * @package      system.core
 * @since		 Version 1.0
 * @version      $Id app.php 2014-11-21 00:52:00 $
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
    public static $_packagePathMap = array();
    private static $_classMap = array();
    private $appPath;
    /**
     * 初始化应用程序
     * @param array $config
     */
    public  function init($config){
    	if (isset($config['appPath'])){
    		$this->setAppPath($config['appPath']);
    	}else{
    		$this->setAppPath('app');
    	}
    	self::setPackagePath('app', $this->getAppPath());
    	$this->configure($config);

    }
    public function __get($name){
    	$method = 'get'.$name;
    	if (method_exists($this,$method)){
    		return $this->$method();
    	}
    }
    public function __set($name,$value){
    	$method = 'set'.$name;
    	if (method_exists($this,$method)){
    		return $this->$method($value);
    	}
    }
    /**
     * 导入包
     * @param unknown $package
     * @param string $forceInclude
     * @return multitype:|Ambigous <multitype:, string, boolean, multitype:>
     */
    public static function import($package,$forceInclude = false){

        if (!$forceInclude && isset(self::$_imports[$package])){
        	
            return self::$_imports[$package];
        }
        if (($key = strpos($package, '.')) === false){//包名格式不正确
        	return false;
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
    
            return self::$_imports[$package]= $packagePath;
        }else{
        	throw new Exception('The package does not exist or packet path is not set');
        }
    }
    /**
     * 类自动加载器
     * @param string $className
     * @return void
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
    /**
     * 获取包路径
     * @param unknown $package
     * @return multitype:|string|boolean
     */
    public static function getPackagePath($package){
        if(isset(self::$_packagePathMap[$package])){
            return self::$_packagePathMap[$package];
        }elseif (($pos = strpos($package, '.')) !== false){
        	$base= substr($package,0,$pos);
        	if (isset(self::$_packagePathMap[$base])){
         		  return  self::$_packagePathMap[$package] =  rtrim(self::$_packagePathMap[$base].DS.str_replace('.', DS, substr($package,$pos+1)),'*'.DS);
        	}
        }
        return false;
    }
    /**
     * 设置包的路径
     * 当某个包没有设置包路径时，app::import将无法正常工作。
     * @param string $package
     * @param string $path
     * @return void
     */
    public static function setPackagePath($package,$path){
    	if (empty($path)){
    		unset(self::$_packagePathMap[$package]);
    	}else {
    		self::$_packagePathMap[$package] = $path;
    	}
    }
    /**
     * 导入包
     * @param string $package
     * @param stirng $path
     */
    public static function uses($package,$path=null){
    	if ($path){
    		self::setPackagePath($package, $path);
    	}
    	self::import($package);
    }
     
     /**
      * 配置应用
      * 如果app类中不存在与$config数组的键名相同的属性，就会通过魔术方法__set()，查找app类中是否存在$config数组的键名加set前缀的方法，
      * 存在便调用。
      * @param array $config
      */
     private function configure($config){
     	if (is_array($config)){
     		foreach ($config as $key=>$value){
     			$this->$key=$value;
     		}
     	}
     }
     /**
      * 导入应用程序类包
      * 此方法导入的是app::init($config)中$config数组中的import单元的值，
      * 且import必需是一个数组,调用步骤:
      * app::configure($config)
      * app::__set($name,$value)
      * app::setImport($packages)
      * @param array $packpages
      * @return void
      */
     private function setImport($packpages){
     	foreach ($packpages as $package){
     		app::import($package);
     	}
     }  
     /**
      * 取得当前应用主目录
      * @return string
      */   
     public function getAppPath(){
     	return $this->appPath;
     }
     /**
      * 设置当前应用的主目录
      * @param string $path
      * @return void
      */
     public function setAppPath($path = null){
     	if (($this->appPath = realpath($path)) === false || !is_dir($path) ){
     		exit("Application base path $path is not a valid directory");
     	}
     }
}
?>