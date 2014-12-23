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
	 * 根据用户请求调用控制器和方法
	 * action为控制器类中的公共方法
	 */
	public function dispatch(){
		$controller = $this->getController();
		if (!($controller instanceof controller)){
			
		}
	}
	
	public function getController(){
		$ctl = isset($_GET['ctl'])? $_GET['ctl'] : (isset($_POST['ctl']) ? $_POST['ctl']: 'index');
		$reflection = new ReflectionClass($ctl);
		if ($reflection->isAbstract() || $reflection->isInterface()){
			return false;
		}
		return $reflection->newInstance();
	}

}

?>