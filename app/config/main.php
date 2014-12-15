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
		'appPath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'import'=>array(
				'app.controller',
				'app.model',
		),
		'db'=>array(
				'db_type'=>'mysql',
				'host'=>'localhost',
				'user'=>'root',
				'password'=>123456,
				'dbname'=>'test2',
				'charset'=>'utf8',
				'tablePrefix'=>'tbl'
		)
)
?>