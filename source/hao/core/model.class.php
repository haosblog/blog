<?php
/**
 * copyright 小皓 (C)2013-2099 版权所有
 *
 * 模型基类
 */
class model {
	var $sql;
	var $tablenameOri;
	var $tablename;
	var $modelname;
	public $param;
	protected $fieldsSetting, $validate;

	public $data = array();				//查询到的数据存储数组
	public $count = 0;					//总数统计,用于分页用

	//Author 小皓。
	//date 2014-5-9
	//新增自动验证、自动装载部分
	protected $options = array();				//2014-5-12新增，用于进行链式操作的参数数组
	protected $_scope = array();				//2014-7-6新增，用于命名范围定义属性
	protected $_fieldsSetting = array();		//用于数据筛选用的字段设置
	protected $_validate = array();				//用于自动验证
	protected $_errMsg = '';					//自动验证产生的报错信息
	protected $_errLevel = 0;					//错误等级
														//0=>没有错误，
														//1=>严重错误，无法执行任何操作
														//2=>无害错误。部分必填信息为空，但用于执行update操作并无妨


	public function __construct($tableName = ''){
		$this->getTableName($tableName);

		if(property_exists($this, 'tableObj')){
			$this->tableObj = C::t($this->tablenameOri);
		}
	}

	/**
	 * 用于在分表查询中更改表名
	 *
	 * @param type $tableid
	 * @return \model 返回自身，支持链式操作
	 */
	public function setTable($tableid){
		$this->tablename = DB::table($this->tablenameOri) .'_'. $tableid;

		return $this;
	}

	/**
	 * 重置分表中的表名为原始名
	 *
	 * @return \model 返回自身，支持链式操作
	 */
	public function resetTable(){
		$this->tablename = DB::table($this->tablenameOri);

		return $this;
	}

	public function query($sql = ''){
		if(empty($sql)){
			if(!empty($this->options)){
				$sql = $this->parseOptionSQL($field, $where);
			} else {
				$sql = $this->sql;
			}
		}

		return DB::query($sql);
	}

	public function queryParse($field = '', $where = '', $orderby = '', $limit = ''){
		if(!empty($this->options)){
			$sql = $this->parseOptionSQL($field, $where);
		} else {
			$sql = $this->parseSQL($field, $where, $orderby, $limit);
		}

		return $this->query($sql);
	}

	public function insert($data, $getid = false){
		return DB::insert($this->tablenameOri, $data, $getid);
	}

	public function insertList($data){
		$sql = "INSERT INTO ". $this->tablename;
		$sqlFieldArr = array();
		$sqlValueArr = array();
		$first = true;

		foreach($data as $item){
			if(!is_array($item)){
				return false;
			}

			if($first){
				$sqlFieldArr = array_keys($item);

				$sqlFieldStr = implode('`,`', $sqlFieldArr);
				$first = false;
			}

			$tmp = implode('\',\'', $item);
			$tmp = "('$tmp')";
			$sqlValueArr[] = $tmp;
		}

		$sqlValueStr = implode(',', $sqlValueArr);
		$sql .= "(`$sqlFieldStr`) VALUES $sqlValueStr";
		$this->query($sql);
	}

	public function update($data, $where = '', $computs = false){
		$sqlwhere = $this->parseWhere($where);
		$fields = $this->parseUpdate($data, $computs);
		$sql = "UPDATE {$this->tablename} SET {$fields}";
		if(!empty($where)){
			$sql .= $this->parseWhere($where);
		}

		return $this->query($sql);
	}


	public function delete($id, $field = 'id'){
		if(is_numeric($id)){
			$where = " WHERE `{$field}`='{$id}'";
		} elseif(is_array($id)) {
			$where = $this->parseWhere($id);
		} else {
			return false;
		}
		$sql = "DELETE FROM {$this->tablename} {$where}";

		$this->query($sql);
	}

	public function total($wheresql, $table = ''){
		$table = empty($table) ? $this->tablename : $table;
		$sql = "SELECT COUNT(*) AS total FROM $table";
		if(!empty($wheresql)){
			$sql .= " WHERE {$where}";
		}

		$data = $this->fetchOne($sql);
		return $data['total'];
	}


	/**
	 * 多表连结查询，此处是最简单的查询方式
	 *
	 * @param type $tables
	 * @param type $field
	 * @param type $where
	 * @param type $orderby
	 * @param type $limit
	 * @return type
	 */
	public function selectJoin($tables, $field = '', $where = '', $orderby = '', $limit = '', $type = ''){
		$sql = $this->parseSQLJoin($tables, $field, $where, $orderby, $limit, $type);

		return $this->fetchList($sql);
	}

	/**
	 * 链式操作中的field
	 *
	 * @param type $field
	 * @return \M_Model
	 */
	final public function field($field){
		if(func_num_args() > 1){
			$field = func_get_args();
		}
		//如果传入的field为true，则载入所有字段
		if(true === $field) {
			$field = '*';
		}

		$this->options['fields'] = $field;
		return $this;
	}

	/**
	 * 根据命名范围构造SQL条件。命名范围在对应模型对象中定义$_scope属性
	 * 本函数将根据$_scope以及传入的scope重新构造option，如果原来链式操作已经设置过对应内容，则合并此操作
	 *
	 * @date 2014-7-6 11:11
	 * @param type $name
	 * @return \M_Model
	 */
	final public function scope($name){
		$scope = $this->_scope[$name];
		$optionArr = array('field', 'where', 'order', 'limit', 'group');

		foreach($optionArr as $item){
			if(isset($scope[$item])){//如果命名范围中定义过该操作，则将操作写入options中
				if(isset($this->options[$item])){//如果options已存在该操作记录，则调用对应合并方法进行合并
					$functionNmae = 'merge'. ucfirst($item);
					$this->options = call_user_func(array($this, $functionNmae), array($this->options[$item], $scope[$item]));
				} else {
					$this->options[$item] = $scope[$item];
				}
			}
		}

		if(func_num_args() > 1){//参数不止一个，则递归调用自身将每个参数都处理一遍
			$args = func_get_args();
			unset($args[0]);//剔除第一个，因为已经处理过
			foreach($args as $item){
				$this->scope($item);
			}

		}

		return $this;
	}

	/**
	 * 链式操作Where参数拼装
	 *
	 * @param type $where
	 * @return \model
	 */
	final public function where($where){
		$this->options['where'] = $where;
		return $this;
	}

	final public function group($group){
		$this->options['group'] = $group;
		return $this;
	}

	final public function order($orderby){
		$this->options['orderby'] = $orderby;
		return $this;
	}

	final public function limit($limit){
		$this->options['limit'] = $limit;
		return $this;
	}


	/**
	 * 链式操作中的联表定义
	 *
	 * @author 小皓
	 * @add 2014-5-12
	 * @param type $table	表名
	 * @param type $on		关联条件，SQL语句
	 * @param string $type	联表类型，默认为空，可选择 LEFT 或 RIGHT
	 * @return obj $this
	 */
	final public function join($table, $on, $type = ''){
		$this->options['join'][] = array(
			'table' => DB::table($table),
			'on' => $on,
			'type' => $type
		);
		return $this;
	}

	/**
	 * 链式操作设置别名
	 *
	 * @param type $alias
	 * @return \model
	 */
	public function alias($alias){
		$this->options['alias'] = $alias;
		return $this;
	}

	/**
	 * 从数据库中读取记录
	 *
	 * @param type $field
	 * @param type $where
	 * @param type $limit
	 */
	public function select($field = '', $where = '', $orderby = '', $limit = '', $total = false){
		if(!empty($this->options)){
			$sql = $this->parseOptionSQL($field, $where);
		} else {
			$sql = $this->parseSQL($field, $where, $orderby, $limit);
		}

		return $this->fetchList($sql, $total);
	}

	/**
	 * 根据条件，返回一条记录
	 *
	 * @param type $field
	 * @param type $where
	 */
	public function selectOne($field = '', $where = '', $orderby = ''){
		if(!empty($this->options)){
			$sql = $this->parseOptionSQL($field, $where);
		} else {
			$sql = $this->parseSQL($field, $where, $orderby);
		}

		$sql .= ' LIMIT 1';

		return $this->fetchOne($sql);
	}

	/**
	 * 根据SQL执行结果返回一条记录
	 *
	 * @param type $query
	 * @return type
	 */
	public function fetch($query){
		return DB::fetch($query);
	}

	/**
	 * 根据SQL代码读取数据列表
	 *
	 * @param string $sql
	 * @param bool $total	是否对返回结果进行统计
	 */
	public function fetchList($sql, $total = false){
		return DB::fetch_all($sql);
	}

	public function fetchOne($sql){
		return DB::fetch_first($sql);
	}

	/**
	 *
	 *
	 * @param type $data
	 * @return \model
	 */
	public function create($data = array()){
		if(count($data) == 0) { $data = $_POST; }
		foreach($data as $key => $value){

		}
		return $this;
	}


	/**
	 * 修改模型名
	 *
	 * @param type $name
	 */
	public function setModelName($name = ''){
		$this->modelname = $name;
		$this->getTableName();

		return $this;
	}

	protected function getModelName(){
		$classname = get_called_class();
		$this->modelname = substr($classname,0,-5);
		return $this->modelname;
	}

	protected function getTableName($tableName = '') {
		if(empty($this->modelname)){
			$this->getModelName();
		}

		$oriTableName = $tableName ? $tableName : $this->modelname;
		$this->tablenameOri = $oriTableName;
		$this->tablename = DB::table($oriTableName);

		return $this->tablename;
	}


	protected function getWsid($wsid){
		if($wsid === false){
			return $GLOBALS['wsid'];
		} else {
			return intval($wsid);
		}
	}


	/**
	 * 根据链式操作生成SQL代码
	 * @auth 小皓
	 * @date 2014-5-13
	 * @return string SQL语句
	 */
	final protected function parseOptionSQL($fieldsParam = '', $whereParam = ''){
		$fields = $where = $orderby = $limit = $join = '';
		if(isset($this->options['fields'])){
			$fields = $this->options['fields'];
			unset($this->options['fields']);
		} elseif(!empty($fieldsParam)){
			$fields = $fieldsParam;
		}

		if(isset($this->options['where'])){
			$where = $this->options['where'];
			unset($this->options['where']);
		} elseif(!empty($whereParam)){
			$where = $whereParam;
		}

		if(isset($this->options['orderby'])){
			$orderby = $this->options['orderby'];
			unset($this->options['orderby']);
		}

		if(isset($this->options['group'])){
			$group = $this->options['group'];
			unset($this->options['group']);
		}

		if(isset($this->options['limit'])){
			$limit = $this->options['limit'];
			unset($this->options['limit']);
		}

		if(isset($this->options['join'])){
			$join = $this->options['join'];
			unset($this->options['join']);
		}

		return $this->parseSQL($fields, $where, $orderby, $limit, $join, $group);
	}

	/**
	 * 联表查询中使用到的字段合并规则，未来将删除
	 *
	 * @param type $field
	 * @return type
	 */
	protected function parseFieldJoin($field){
		$sqlfield = '';
		if(is_array($field)){
			$fieldArr = array();
			foreach ($field as $key => $value){
				if(is_array($value)){
					$tmp = implode('`, '. $key .'.`', $value);
					$tmp = $key .'.`'. $tmp .'`';
					$fieldArr[] = $tmp;
				} else {
					$fieldArr[] = $value;
				}
			}
			$sqlfield = implode(',', $fieldArr);
		} else {
			$sqlfield = $field;
		}

		return $sqlfield;
	}

	/**
	 * 生成UPDATE、INSERT所需的字段拼接
	 *
	 * @param array $arr		需要拼接的数据
	 * @param type $computs	是否在UPDATE中执行运算
	 */
	protected function parseUpdate($arr, $computs = false){
		if($computs){
			$tmpArr = array();
			foreach($arr as $key => $value){
				$markArr = array('+', '-', '*', '/');
				$mark = '';
				foreach($markArr as $item){
					if(strpos($value, $item) !== false){
						$mark = $item;
						break;
					}
				}

				if($mark){
					list($flag, $num) = explode($mark, $value);
					$fieldName = '`'. $key .'`';
					$tmpArr[] = $fieldName .'='. (empty($flag) ? $fieldName : '`'. $flag .'`') .
							$mark . DB::quote($num);
				}
			}
		} else {
			$return = DB::implode($arr);
		}

		return $return;
	}

	/**
	 * 生成联表查询的表结构语句
	 *
	 * @param mixed $tables	表列表
	 * @param string $type	TODO 联表类型，留待未来扩展，可设置RIGHT JOIN或LEFT JOIN
	 * @return type
	 */
	protected function parseTables($tables, $type = ''){
		$sqltable = '';
		if(is_array($tables)){
			$tableArr = array();
			foreach ($tables as $key => $value){
				$tmp = '`'. DB::table($value) .'` AS '. $key;
				$tableArr[] = $tmp;
			}

			$sqltable = implode(',', $tableArr);
		} else {
			$sqltable = $tables;
		}

		return $sqltable;
	}

	/**
	 * 根据输入的参数生成SQL代码
	 *
	 * @auth 小皓
	 * @date 2013-11-20
	 * @param type $field
	 * @param type $where
	 * @param type $orderby
	 * @param type $limit
	 * @param type $join
	 * @return string
	 */
	final protected function parseSQL($field = '', $where = '', $orderby = '', $limit = '', $join = array(), $group = ''){
		$joinBool = !empty($join);
		$sqlfield = $this->parseField($field, $joinBool);
		$sqlwhere = $this->parseWhere($where, $joinBool);
		$sqlorderby = $this->parseOrder($orderby);
		$sqllimit = $this->parseLimit($limit);
		$sqlgroup = $this->parseGroup($group);
		$sqltable = $this->parseTable($join);

		$sql = 'SELECT '. $sqlfield .' FROM '. $sqltable .' '. $sqlwhere .' '. $sqlorderby .' '. $sqllimit;

		return $sql;
	}

	/**
	 * 字段和表名添加`
	 * 保证指令中使用关键字不出错 针对mysql
	 * @access protected
	 * @param mixed $value
	 * @return mixed
	 */
	protected function parseKey(&$value, $alias = '') {
		$value   =  trim($value);
		if( false !== strpos($value,' ') || false !== strpos($value,',') || false !== strpos($value,'*') ||  false !== strpos($value,'(') || false !== strpos($value,'.') || false !== strpos($value,'`')) {
			//如果包含* 或者 使用了sql方法 则不作处理
		} else {
			$value = '`'.$value.'`';
			if(!empty($alias) && is_string($alias)){
				$value = $alias .'.'. $value;
			}
		}
		return $value;
	}


	/**
	 * value分析
	 * @access protected
	 * @param mixed $value
	 * @return string
	 */
	protected function parseValue($value) {
		if(is_string($value)) {
			$value = '\''.$value.'\'';
		}elseif(isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp'){
			$value   =  $value[1];
		}elseif(is_null($value)){
			$value   =  'null';
		}
		return $value;
	}

	/**
	 * 根据传入的field值生成SQL需要读取的字段内容
	 * 2014-5-20 change by 小皓 新增多表联结自动添加别名操作
	 *
	 * @auth 小皓
	 * @date 2013-11-20
	 * @param type $field
	 * @return string
	 */
	final protected function parseField($field, $join = false){
		$sqlfield = '';
		if(is_array($field)){
			$sqlfield = $this->_parseField($field, $join);
		} else {
			$sqlfield = $field;
		}
		//删除拼接出来后末尾的逗号
		$sqlfield = rtrim($sqlfield, ',');

		if(empty($sqlfield)){
			$sqlfield = '*';
		}

		return $sqlfield;
	}

	/**
	 * 私有的字段拼接方法，可递归调用
	 *
	 * @param mixed $field	需要拼接的字段
	 * @param mixed $alias	如果传入的是布尔型，说明当前查询是多表联结查询，需要判断是否自动加别名。如果传入的是字符串，则为当前表别名，自动拼接
	 */
	private function _parseField($field, $alias = ''){
		$sqlfield = '';
		foreach($field as $key => $val){
			if($alias === true && is_string($key) && is_array($val)){//如果是多表联结，且$key为字符串$val为数组，则以key为别名继续拼装
				$sqlfield .= $this->_parseField($val, $key);		//递归调用自身
			} elseif(is_string($alias)) {
				$sqlfield .= $this->parseKey($val, $alias) .',';
			} else {
				$sqlfield .= $this->parseKey($val) .',';
			}
		}

		return $sqlfield;
	}

	/**
	 * 根据传入的where变量生成判断条件
	 * 2014-5-17 change by 小皓 从TP中复制了同名方法，并删除不需要的部分，将中间部分拆分至私有方法，现支持多维数组拼装复杂的WHERE操作了
	 * 2014-5-20 change by 小皓 添加自动加别名方法
	 *
	 * @auth 小皓
	 * @since 2013-11-20
	 * @param mixed $where
	 * @param boolean $join
	 * @return string
	 */
	protected function parseWhere($where, $join = false){
		if($join && is_array($where)){//如果是联表查询，且where是数组，则判断自动加别名if(isset($where['_logic'])) {
			// 定义逻辑运算规则 例如 OR XOR AND NOT
			if(isset($where['_logic'])) {
				$logic    =   ' '.strtoupper($where['_logic']).' ';
				unset($where['_logic']);
			} else {
				// 默认进行 AND 运算
				$logic    =   ' AND ';
			}
			foreach($where as $key => $val){
				if(is_string($key) && is_array($val)){//如果$key为字符串，且val是数组，则以key为别名拼接where
					$sqlwhere .= $logic . $this->_parseWhere($val, $key);
				} else {
					$sqlwhere .= $logic . $this->_parseWhere($where);
					break;
				}
			}

			$sqlwhere = substr($sqlwhere, strlen($logic));
		} else {
			$sqlwhere = $this->_parseWhere($where);
		}

		if(!empty($where) && strpos(strtolower($sqlwhere), 'where') === false){//自动拼接where
			$sqlwhere = ' WHERE '. $sqlwhere;
		}

		return $sqlwhere;
	}

	/**
	 * 拼接WHERE SQL语句私有方法，可用于重复拼接
	 *
	 * @auth 小皓
	 * @since 2014-5-17
	 * @param mixed $where
	 * @param boolean $join
	 * @param string $alias
	 * @return type
	 */
	private function _parseWhere($where, $alias = ''){
		$sqlwhere = '';
		if(is_string($where)) {
			// 直接使用字符串条件
			$sqlwhere = $where;
		} elseif(is_array($where)) { // 使用数组条件表达式
			if(isset($where['_logic'])) {
				// 定义逻辑运算规则 例如 OR XOR AND NOT
				$logic    =   ' '.strtoupper($where['_logic']).' ';
				unset($where['_logic']);
			}else{
				// 默认进行 AND 运算
				$logic    =   ' AND ';
			}

			if(isset($where['_op'])){//定义了运算符则
				$operator = strtolower($where['_op']);
				unset($where['_op']);

				$search = array('eq', 'ne', 'gt', 'ge', 'lt', 'le');
				$operatorArr = array('=', '!=', '>', '>=', '<', '<=');

				if(!in_array($operator, $operatorArr)){//定义的运算符为转义，则替换
					$operator = str_replace($search, $operatorArr, $operator);
				}
			} else {//未定义默认为=
				$operator = '=';
			}

			foreach ($where as $key=>$val){
				$sqlwhere .= "( ";

				if(is_array($val)) {//$val为数组，调用_parseMultiWhere拼接
					$sqlwhere .= $this->_parseMultiWhere($key, $val, $alias, $operator);
				} elseif(is_string($key)){//如果key为字符串，则拼接为$key=$val的格式
					$key = $this->parseKey($key, $alias);
					$sqlwhere .= $key . $operator . $this->parseValue($val);
				} else {//如果$key不为字符串，则代表$val是一段完整的SQL语句，直接拼装
					$sqlwhere .= $val;
				}

				$sqlwhere .= ' )'. $logic;
			}

			$sqlwhere = substr($sqlwhere,0, -strlen($logic));
		}

		return $sqlwhere;
	}


	/**
	 * 当使用数组拼接Where的时候调用本方法处理
	 *
	 * @param mixed $field	当field为字符串时，则拼接为field IN('1', '2', '3')，否则调用_parseWhere将value拼接为新的SQL
	 * @param array $value
	 * @return type
	 */
	private function _parseMultiWhere($field, $value, $alias = '', $op = '='){
		if(is_string($field)){//$val为数组且$key为字符串，则拼接为field IN ('1', '2', '3')的格式
			$valueList = implode('\',\'', $value);

			$operator = $op == '=' ? 'IN' : 'NOT IN';
			$sqlwhere = $this->parseKey($field, $alias) ." {$operator} ('{$valueList}')";
		} else {
			$sqlwhere = $this->_parseWhere($value, $alias);
		}

		return $sqlwhere;
	}

	/**
	 * 根据参数生成排序SQL
	 *
	 * @auth 小皓
	 * @date 2013-11-20
	 * @param type $orderby
	 * @return string
	 */
	final protected function parseOrder($orderby) {
		$sqlorder = '';
		if(is_array($orderby)){
			$tmpArr = array();
			foreach($orderby as $key => $value){
				$tmpStr = $key;
				if($value){
					$tmpStr .= ' DESC';
				}

				$tmpArr[] = $tmpStr;
			}

			$sqlorder = implode(',', $tmpArr);
		} else {
			$sqlorder = $orderby;
		}

		if(!empty($sqlorder) && strpos($sqlorder, 'ORDER BY') === false){
			$sqlorder = ' ORDER BY '. $sqlorder;
		}

		return $sqlorder;
	}

	/**
	 * 格式化group后的内容
	 *
	 * @auth 小皓
	 * @date 2014-6-8
	 * @param type $group
	 */
	final protected function parseGroup($group){
		if(is_array($group)){
			$group = 'GROUP BY '. $this->_parseGroup($group);
		} else {
			$group = 'GROUP BY '. $group;
		}

		return $group;
	}

	/**
	 * 格式花数组格式的group
	 *
	 * @auth 小皓
	 * @date 2014-6-8
	 * @param type $group
	 * @param type $alias
	 * @return type
	 */
	private function _parseGroup($group, $alias = ''){
		$sqlgroup = '';
		foreach($group as $key => $val){
			if(is_string($val)) {
				$sqlgroup .= $this->parseKey($val);
			} elseif(is_array($val) && is_string($key)){
				$sqlgroup .= $this->_parseField($val, $key) .',';
			}
		}

		return rtrim($sqlgroup, ',');
	}

	/**
	 * 根据传入的limit值生成范围
	 *
	 * @auth 小皓
	 * @date 2013-11-20
	 * @param type $limit
	 */
	final protected function parseLimit($limit){
		if(is_array($limit)){
			$countlimit = count($limit);
			$sqllimit = $countlimit >= 2 ? "{$limit[0]}, {$limit[1]}" : '';
		} else {
			$sqllimit = $limit;
		}

		if(!empty($sqllimit) && strpos($sqllimit, 'LIMIT') === false){
			$sqllimit = ' LIMIT '. $sqllimit;
		}

		return $sqllimit;
	}

	/**
	 * 根据传入的join参数生成table内容
	 *
	 * @auth 小皓
	 * @date 2014-5-13
	 * @param array $join
	 * @return string
	 */
	final protected function parseTable($join = array()){
		$sqltable = $this->tablename;
		if(isset($this->options['alias'])){
			$sqltable .= ' AS '. $this->options['alias'];
			unset($this->options['alias']);
		}
		if(!empty($join)){
			foreach($join as $item){
				$sqltable .= ' '. $item['type'] .' JOIN ' .$item['table'];
				if(!empty($item['on'])){
					$sqltable .= ' ON '. $item['on'];
				}
			}
		}

		return $sqltable;
	}



	/**
	 * 通过参数生成联表查询的SQL语句
	 *
	 * @param type $tables
	 * @param type $field
	 * @param type $where
	 * @param type $orderby
	 * @param type $limit
	 * @param type $type		联表类型，TODO，根据$type将生成对应的联表方式（LEFT JOIN、RIGHT JOIN），见parseTable方法
	 * @return type
	 */
	protected function parseSQLJoin($tables, $field = '', $where = '', $orderby = '', $limit = '', $type = ''){
		$sqltable = $this->parseTables($tables, $type);
		$sqlfield = $this->parseFieldJoin($field);
		$sqlwhere = $this->parseWhere($where);
		$sqllimit = $this->parseLimit($limit);
		$sqlorderby = $this->parseOrder($orderby);

		$sql = "SELECT {$sqlfield} FROM {$sqltable} {$sqlwhere}  {$sqlorderby}";

		if(!empty($sqllimit)){
			$sql .= " LIMIT $sqllimit";
		}

		return $sql;
	}

	/**
	 * 筛选数据
	 *
	 * @param type $param
	 */
	protected function initParam($param) {
		$newParam = array();
		$varname = $this->fieldsSetting;
		foreach($varname as $key => $name) {
			//如果传过来的值中存在
			if(isset($param[$key])) {
				$newParam[$key] = $param[$name];
			} elseif(isset($name['default'])) {
				$newParam[$key] = $name['default'];
			}
		}

		$this->_fieldsSetting = $newParam;
		return $newParam;
	}

	/**
	 * 验证数据
	 *
	 * @param type $param
	 */
	protected function validateParam($param) {
		$rule = $this->validate;

	}
}
?>