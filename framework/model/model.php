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
	public $dbname='';
	public $model = '';
	public $table = '';
	public $tablePrefix = '';
	public $options =array();
	public $methods = array('table','count','having','order','alias','group','distinct');
	public function __construct($name='',$connection=''){
		$connection = !empty($connection) ? $connection : configure::read('app.db');
		if (!isset($conection['tablePrefix'])){
			$this->tablePrefix = '';
		}elseif (!empty($connection['tablePrefix'])){
			$this->tablePrefix = $connection['tablePrefix'];
		}
		if (!empty($name)){
			if (strpos($name, '.') !== false){
				list($this->dbname,$this->model) = explode('.', $name);
			}else{
				$this->model = $name;
			}
		}elseif (empty($this->model)){
			$this->model = $this->getModelName();
		}
		$this->db = db::instance($connection);
	}
	public function __call($method,$args){
		if (in_array($method, $this->methods)){
			$this->options[$method] = $args[0];
			return $this;
		}elseif(in_array($method, array('count','sum','max','min','avg'))){
			$field= isset($args[0]) && !empty($args[0]) ? $args[0] : '*';
			$this->getCount(strtoupper($method).'('.$field.')'.' AS pm_'.$method,'pm_'.$method);
		}
	}
	public function select($options=array()){
		if (is_string($options)){
			$where = $options;
			$options = array();
			$options['where'] = $where;
		}
		$options = $this->parseOptions($options);
		$res = $this->db->select($options);
		if ($res == false){
			return false;
		}
		if ($res == null){
			return null;
		}
		return $res;
	}
	public function field($fields){
		if (is_string($fields)){
			$fields = explode(',', $fields);
		}
		$this->options['field'] = $fields;
		return $this;
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
	public function parseOptions($options){
		if (is_array($options)){
			$options = array_merge($this->options,$options);
		}
		if (!isset($options['table']) && empty($options['table'])){
			$options['table'] = $this->getTable();
		}
		if (isset($options['alias'])){
			$options['table'] .= ' '.$options['alias'];
		}
		$this->options = array();
		return $options;
	}
	public function getTable(){
		$table = !empty($this->tablePrefix) ? $this->tablePrefix : '';
		if (!empty($this->tableName)){
			$table .= $this->table;
		}else{
			$table = $this->name;
		}
		return (!empty($this->dbname) ? $this->dbname :'').$table;
	}
	public function getModelName(){
		if (empty($this->model)){
			$this->model = substr(get_class($this),0,-5);
		}
		return $this->model;
	}
	public function getCount($field,$returnField){
		$options['field'] = $field;
		$options = $this->parseOptions($options);
		$result = $this->db->select($options);
		if ($result){
			return $result[$returnField];
		}
	}
}
?>