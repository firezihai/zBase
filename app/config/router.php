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
return array(
	'defaultApp'=>'app',
	'defaultController'=>'index',
	'defaultAction'=>'index',
	/*应用别名设置
	 * 键为别名，值为真实路由，真实路由对应一个目录名
	 * 如果启用的模块，在单独设置某个模块的路由时，必需以直实路由为键名。
	 * */
	'alias'=>array('desktop'=>'admin','test'=>'app'),
	'admin'=>array(
			'defaultController'=>'pages',
			'defaultAction'=>'init',
			'disable'=>false,
			'indexController'=>array(
						 
						 'index'=>true,
						 'display'=>false
			)
	)
)
?>