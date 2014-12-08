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
class model{
	public $db = null;
	public $options =array();
	public function __construct($model='',$connection=''){
		$connection = !empty($connection) ? $connection : configure::read('app.db');
		$this->db = db::instance($connection);
	}
	public function select(){
		$this->db->select();
	}
	public function where($where){
		if (is_string($where) && $where != ''){
			$temp = array();
			$temp[] = $where;
			$where = $temp;
		}
		if (isset($this->options['where'])){
			$this->options['where'] = array_merge($this->options['where'],$where);
		}else{
			$this->options['where'] = $where;
		}
		return $this;
	}
	
}
?>