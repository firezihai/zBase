<?php
/**
 * PALM  Simple And Easy
 *
 * An open source application development framework for PHP 5.1.6 or newer
 * @package            base
 * @copyright         Copyright (c) 2014  zihai  All rights reserved.
 * @author		      子海(zihaidetiandi@sina.com)
 * @copyright	      Copyright (c) 2014 - 2015, www.zihaidetiandi.com
 * @license		      http://www.apache.org/licenses/LICENSE-2.0
 * @link	          www.zihaidetiandi.com/palm/
 * @since		      Version 1.0
 * @filesource
 */
define("ROOT",realpath(dirname(__FILE__)));
define('APP_ROOT', realpath(dirname(dirname(__FILE__))).'/app');
define('APP', 'app');
require_once ROOT.'/app/index.php';
?>