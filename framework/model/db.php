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
class db{
	/**
	 * 数据库驱动实例
	 * @var string
	 */
	public static $instance;
	private $lasSql;
	/**'
	 * 数据库查询语句结构
	 * @var string
	 */
	protected $sql = 'SELECT %DISTINCT% %FIELD% FROM %TABLE% %JOIN% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT% %UNION% %COMMENT%';
	private function __construct(){}
	/**
	 * 单例方法
	 * 配置信息为不同，返回不同的数据库驱动对象例实例
	 * @param array $config
	 */
	public static function instance($config){
		$token = serialize($config);
		if (!isset(self::$instance[$token])){
			$db = new db();
			self::$instance[$token] =$db->factory($config);
		}
		return self::$instance[$token];
	}
	/**
	 * 驱动工厂
	 * 根据数据库配置信息，实例化相应的数据库驱动类，并返回其实例对象
	 * @param array $config
	 * @return string 返回当前数据库驱动类对象实例
	 */
	public function factory($config){
		if (!isset($config['db_type'])){
			trigger_error('Does not define the type of database .', E_USER_ERROR);
		}
		$driver = $config['db_type'];
		if (class_exists($driver)){
			unset($config['db_type']);
			return new $driver($config);
		}
	}
	public function select($options){
		$sql = $this->bulidSql($this->sql,$options);
		$this->lasSql = $sql;
	
		//$result = $this->query($sql);
		//return $result;
	}
	
	protected  function bulidSql($sql,$options){
		$sql = str_replace(
				array('%DISTINCT%','%FIELD%','%TABLE%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%COMMENT%'),
				array(	$this->parseDistinct(isset($options['distinct'])?$options['distinct']:''),
							$this->parseField(isset($options['field'])?$options['field']:''),
						   $this->parseTable(isset($options['table'])?$options['table']:''),
						   $this->parseJoin(isset($options['join'])?$options['join']:''),
						   $this->parseWhere(isset($options['where'])?$options['where']:''),
						   $this->parseGroup(isset($options['group'])?$options['group']:''),
						   $this->parseHaving(isset($options['having'])?$options['having']:''),
						   $this->parseOrder(isset($options['order'])?$options['order']:''),
						   $this->parseLimit(isset($options['limit'])?$options['limit']:''),
						   $this->parseUnion(isset($options['unin'])?$options['unin']:''),
						  $this->parseComment(isset($options['comment'])?$options['comment']:'')
				),
				$sql);
		return $sql;
	}
	protected function parseField($fields){
		if(is_string($fields)&& strpos($fields, ',')){
			$fields = explode(',', $fields);
		}
		if(is_array($fields)){
			$array = array();
			foreach ($fields as $key=>$field){
				if (!is_numeric($key)){
					$array[] = $this->parseKey($key).' AS '.$this->parseKey($field);
				}else{
					$array[] = $this->parseKey($field);
				}
			}
			$fieldStr = implode(',', $array);
		}elseif (is_string($fields) && !empty($fields)){
			$fieldStr = $this->parseKey($fields);
		}else {
			$fieldStr = '*';
		}
		return $fieldStr ;
	}
	protected function parseTable($tables){
		if (is_array($tables)){
			$array = array();
			foreach ($tables as $table=>$alias){
				if (!is_numeric($table)){
					$array[] = $table.' AS '.$alias;
				}else{
					$array[] = $alias;
				}
			}
			$tables = $array;
		}else{
			$tables = explode(',', $tables);
		}
		return implode(',', $tables);
	}
	protected function parseKey(&$key){
		return $key;
	}
	protected function parseValue($value){
		if (is_string($value)){
			$value = '\''.$value.'\'';
		}elseif (is_bool($value)){
			$value = $value ? '1' : '0';
		}elseif (is_null($value)){
			$value = 'null';
		}
		return $value;
	}
	protected function parseWhere($where){
		$whereSql = '';
		if (is_string($where)){
			$whereSql = $where;
		}elseif (is_array($where)){
			$operator = 'AND ';
			foreach ($where as $key=>$v){
				if (!is_numeric($key)&& is_array($v) && in_array(strtoupper($key), array('AND','OR','XOR'))){
					$operator2 = strtoupper($key);
					$whereSql2 = '';
					$whereSql .='(';
					foreach ($v as $field=>$value){
						$temp  = ' '.$this->parseWhereItem($field, $value);
						if ($temp){
							$whereSql2 .=  $temp.'  '.$operator2;
						}
					}
					$whereSql .= substr($whereSql2,0,-strlen($operator2)).' ) AND';
				}else{
					
					$whereSql .=  ' '.$this->parseWhereItem($key, $v);
					$whereSql .= ' AND';
				}
			}
			$whereSql = substr($whereSql,0,-strlen($operator));
		}

		return !empty($whereSql) ? ' WHERE '.$whereSql : '';
	}
	protected function parseWhereItem($key,$v){
		$whereSql = '';
		if (strpos($key,'@') !== false){
			$temp = explode('@', $key);
			if ($temp[1] =='~'){
				$whereSql .= $temp[0].' LIKE %'.$this->parseValue($v).'%';
			}elseif ($temp[1] == '~!'){
				$whereSql .= $temp[0].' LIKE '.$this->parseValue($v).'%';
			}elseif ($temp[1] == '!~'){
				$whereSql .= $temp[0].' LIKE %'.$this->parseValue($v);
			}elseif (in_array($temp[1], array('<>','>','<','=','>=','<='))){
				$logic = $temp[1];
				$whereSql .= $temp[0].$logic.$this->parseValue($v);
			}
		}
		return $whereSql;
	}
	protected function parseJoin($join){
		$joinStr = '';
		if (!empty($join)){
			$joinStr = ' '.implode(' ', $join).' ';
		}
		return $joinStr;
	}
	protected function parseDistinct($distinct){
		return !empty($distinct) ? 'DISTINCT '.$distinct : '';
	}
	protected function parseGroup($group){
		return !empty($group) ? 'GROUP BY '.$group : '';
	}
	protected function parseHaving($having){
		return !empty($having)?'HAVING '.$having : '';
	}
	protected  function parseOrder($order){
		return !empty($order) ? 'ORDER BY  '.$order : '';
	}
	protected  function parseLimit($limit){
		return !empty($limit) ? 'LIMIT '.$limit : '';
	}
	protected function parseUnion($union){
		return !empty($union) ? 'UNION '.$union : '';
	}
	protected function parseComment($comment){
		return !empty($comment) ? '' : '';
	}
	public function lastSql(){
		return $this->lasSql;
	}
	public function __clone(){
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
}
?>