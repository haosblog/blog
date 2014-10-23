<?php
/**
 * File: M_Model.class.php
 * Functionality: Core PDO model class
 * Author: Nic XIE
 * Date: 2013-2-28
 * Note:
 *	1 => This class requires PDO support !
 *	2 => $conn MUST BE set to static for transaction !
 * ---------------- DO NOT MODIFY THIS FILE UNLESS YOU FULLY UNDERSTAND ! -------------
 */

abstract class M_Model {
	//change By 小皓。数据仓库所需字段
	public $data = array();				//查询到的数据存储数组
	public $count = 0;					//总数统计,用于分页用
	private $totalSql = '';


	private static $conn = null;
	private $result = null;				//查询结果
	protected $table = '';				//表名
	protected $originalTable = '';		//原始表名，用于分表

	// Last select sql: used for Memcache
	protected $sql = '';

	// success code of PDO
	private $successCode = '00000';

	// The result of last operation: 0 => failure,  1 => success
	private $success = false;

	// 是否打开单条 SQL 缓存
	protected $cache = false;

	// 单条 SQL 缓存过期
	protected $MEM_EXPIRE = 1800;

	// SQL log file: Log SQL error for debug if not under DEV
	private $logFile = '';

	//Author 小皓。
	//date 2014-5-9
	//新增自动验证、自动装载部分
	protected $_fieldsSetting = array();		//用于数据筛选用的字段设置
	protected $_validate = array();				//用于自动验证
	protected $_errMsg = '';					//自动验证产生的报错信息
	protected $_errLevel = 0;					//错误等级
														//0=>没有错误，
														//1=>严重错误，无法执行任何操作
														//2=>无害错误。部分必填信息为空，但用于执行update操作并无妨
	protected $options = array();				//2014-5-12新增，用于进行链式操作的参数数组

	/**
	 * Constructor
	 * <br /> 1: Connect to MySQL
	 *
	 * @param string => use default DB if parameter is not specified !
	 * @return null
	 */
	function __construct($db = '') {
		$this->logFile = '/var/log/www/sql/'.CUR_DATE.'.log';
		if(!file_exists($this->logFile) && ENVIRONMENT != 'DEV'){
			touch($this->logFile);
		}

		if($db){
			$this->connect($db);
		}else{
			if(!self::$conn){
				$this->connect();
			}
		}
	}


	/**
	 * Connect to MySQL
	 *
	 * @param string => use default DB if parameter is not specified !
	 * @return null
	 */
	private function connect($db = '') {
		include CONFIG_PATH.'/DB_config.php';
		if($db){
			$db = $DB_Config[$db];
		}else{
			$db = $DB_Config['Default'];
		}

		$driver = $DB_Config['TYPE'];
		$host   = $DB_Config['HOST'];
		$user   = $DB_Config['USER'];
		$pswd   = $DB_Config['PSWD'];

		$dsn = $driver.':host='.$host.';dbname='.$db;
		try{
			self::$conn = new PDO($dsn, $user, $pswd);
			self::$conn->query('SET NAMES utf8');
		}catch(PDOException $e){
			if(ENVIRONMENT == 'DEV'){
				Helper::raiseError(debug_backtrace(), $e->getMessage());
			}else{
				file_put_contents($this->logFile, $e->getMessage(), FILE_APPEND);
			}
		}
	}


	/**
	 * Add table prefix
	 *
	 * @param string => target table
	 * @return table with TB_PREFIX
	 */
	public function addPrefix($table) {
		$this->table = TB_PREFIX . $table;
		return $this->table;
	}


	/**
	 * Switch DB
	 *
	 * @param string => target db
	 * @return null
	 */
	public function selectDB($db = '') {
		$this->connect($db);
	}


	/**
	 * Switch target table
	 *
	 * @param string => target table
	 * @return null
	 */
	public function switchTable($targetTable) {
		$this->originalTable = $this->table;
		$this->table = $this->addPrefix($targetTable);
	}


	/**
	 * Set original table back to target table
	 *
	 * @param null
	 * @return null
	 */
	public function recoveryTable() {
		$this->table = $this->originalTable;
		$this->originalTable = '';
	}
	
	/**
	 * 链式操作中的field
	 * 
	 * @param type $field
	 * @return \M_Model
	 */
	final public function Field($field){
		//如果传入的field为true，则载入所有字段
		if(true === $field) {
			$field = '*';
		}

		if(func_num_args() > 1){
			$this->options['fields'] = func_get_args();
		} else {
			$this->options['fields'] = $field;
		}

		return $this;
	}
	
	final public function Where($where){
		if(is_string($where)){
			$where = ' WHERE '. $where;
		}

		$this->options['where'] = $where;
		return $this;
	}
	
	final public function Order($orderby){
		$orderby = strpos($orderby, 'ORDER') === false ? ' ORDER BY '. $orderby : $orderby;

		$this->options['orderby'] = $orderby;
		return $this;
	}
	
	final public function Limit($limit){
		$limit = strpos($limit, 'LIMIT') === false ? ' LIMIT '. $limit : $limit;

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
	final public function Join($table, $on, $type = ''){
		$this->options['join'][] = array(
			'table' => $table,
			'on' => $on,
			'type' => $type
		);
		return $this;
	}


	/**
	 * Select all records
	 *
	 * @param string  => if null or empty, make it '*', else combine all if is an array, use it if string !
	 * @param string  => where condition
	 * @param string  => order by condition
	 * @param string  => limit condition
	 * @param boolean => return numRows
	 * @return records on success or false on failure or numRows if $numRows is true on success
	 */
	final public function Select($fields = '', $where = '', $orderby = '', $limit = '', $numRows = false, $total = true){
		//如果options属性不为空，则执行链式操作
		if(!empty($this->options)){
			$sql = $this->parseOptionSQL($fields, $where);
		} else {
			$sql = $this->parseSQL($fields, $where, $orderby, $limit);
//			pr($sql);die;
//			if ($fields) {
//				if(is_array($fields)){
//					$fields = implode(',', $fields);
//				}else{
//					$fields = $fields;
//				}
//			} else {
//				$fields = ' * ';
//			}
//
//			$sql = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ';
//			if ($where) {
//				$sql .= $where . ' ';
//			}
//
//			if ($orderby) {
//				$sql .= $orderby . ' ';
//			}
//
//			if ($limit) {
//				$sql .= $limit . ' ';
//			}
		}

		$this->sql = $sql;
		//echo $sql;

		// Data from this SQL already in Memcache ?
		if(!defined('ADMIN_MODE') && MEMCACHED == 1 && MEM_EXCEPTION != 1){
			if($this->cache){
				$key = md5($this->sql);
				$data = $GLOBALS['memcache']->get($key);

				if($data){
					//br();
					//echo '___Data from Memcache___';
					return $data;
				}else{
					//br();
					//echo '___Data from MySQL___';
				}
			}else{
				//br();
				//echo '___Cache is disabled, Have to get data from MySQL___';
			}
		}

		$this->Execute();
		$return = $this->success ? $this->Fetch() : null;

		if($total){
			$totalQuery = $this->Query($this->totalSql);
			$this->count = $totalQuery[0]['count'];
		}

		if($numRows){
			return $total;
		}

		return $return;
	}
	
	public function Alias($alias){
		$this->options['alias'] = $alias;
		return $this;
	}


	/**
	 * Select one record
	 *
	 * @param string  => if null or empty, make it '*', else combine all if is an array, use it if string !
	 * @param string  => where condition
	 * @param string  => order by condition
	 * @return records on success or false on failure or numRows if $numRows is true on success
	 */
	final public function SelectOne($fields = '', $where = '', $orderby = ''){
		$limit = (is_string($where) && stripos($where, 'LIMIT') !== false) || (is_string($orderby) && stripos($orderby, 'LIMIT') !== false) ? '' : 'LIMIT 1';
		$query = $this->Select($fields, $where, $orderby, $limit, false, false);

		$data = null;
		if(is_array($query) && !empty($query)){
			$data = $query[0];
		}

		$this->data = $data;
		return $data;
	}


	/**
	 * Insert | Add a new record
	 *
	 * @param Array => Array('field1'=>'value1', 'field2'=>'value2', 'field3'=>'value1')
	 * @return false on failure or inserted_id on success
	 */
	final public function Insert($maps = array()) {
		if($this->_errLevel != 0){//致命错误不可执行insert操作
			return false;
		}
		$maps = empty($maps) ? $this->data : $maps;
		if (!$maps || !is_array($maps)) {
			return false;
		} else {
			$fields = $values = array();

			foreach ($maps as $key => $value) {
				$fields[] = '`' . $key . '`';
				$values[] = "'$value'";
			}

			$fieldString   = implode(',', $fields);
			$valueString = implode(',', $values);

			$sql = 'INSERT INTO ' . $this->table . " ($fieldString) VALUES ($valueString)";
			$this->sql = $sql;

			$this->Execute();

			return $this->success ? $this->getInsertID() : null;
		}
	}


	/**
	 * Insert | Add a list record
	 *
	 * @param type $data
	 * @return boolean
	 */
	public function MultiInsert($data){
		$sql = "INSERT INTO ". $this->table;
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

		$this->sql = $sql;
		$this->Execute();

		return $this->success ? $this->getInsertID() : null;
	}

	/**
	 * Replace | Add a new record if not exit, update if exits;
	 *
	 * @param Array => Array('field1'=>'value1', 'field2'=>'value2', 'field3'=>'value1')
	 * @return false on failure or inserted_id on success
	 */
	final public function ReplaceInto($maps) {
		if (!$maps || !is_array($maps)) {
			return false;
		} else {
			$fields = $values = array();

			foreach ($maps as $key => $value) {
				$fields[] = '`' . $key . '`';
				$values[] = "'$value'";
			}

			$fieldString   = implode(',', $fields);
			$valueString = implode(',', $values);

			$sql = 'REPLACE INTO ' . $this->table . " ($fieldString) VALUES ($valueString)";
			$this->sql = $sql;

			$this->Execute();

			return $this->success ? $this->getInsertID() : null;
		}
	}


	/**
	 * Return last inserted_id
	 *
	 * @param null
	 * @return the last inserted_id
	 */
	public function getInsertID() {
		return self::$conn->lastInsertId();
	}


	/**
	 * Fetch data
	 *
	 * @param boolean => if set to true, memcached will cached this SQL data
	 * @return data from DB
	 */
	private function Fetch() {
		$data = $this->result->fetchAll(PDO::FETCH_ASSOC);
		if(!defined('ADMIN_MODE') && MEMCACHED == 1 && MEM_EXCEPTION != 1 && $this->cache){
			$GLOBALS['memcache']->set(md5($this->sql), $data, 0, $this->MEM_EXPIRE);
		}
		return $data;
	}


	/**
	 * Calculate record counts
	 *
	 * @param string => where condition
	 * @return int => total record counts
	 */
	final public function Total($where = '') {
		if(isset($this->options['where'])){
			$where = $this->options['where'];
			unset($this->options['where']);
		}
		$data = $this->SelectOne('COUNT(*) AS `total`', $where);
		return $data['total'];
	}


	/**
	 * Execute SELECT | INSERT SQL statements
	 *
	 * <br /> Remark:  If error occurs and UAT is true, call raiseError() to display error and halt !
	 * @param string => SQL statement to execute
	 * @return result of execution
	 */
	final private function Execute() {
		$this->result = self::$conn->query($this->sql);
		$this->checkResult();
	}


	/**
	 * Update record(s)
	 *
	 * @param array  => $maps = array('field1'=>value1, 'field2'=>value2, 'field3'=>value3))
	 * @param string => where condition
	 * @param boolean $self => self field ?
	 * @return false on failure or affected rows on success
	 */
	final public function Update($maps, $where = '', $self = false) {
		if (!$maps) {
			return false;
		} else {
			$sql = 'UPDATE ' . $this->table . ' SET ';
			$sets = array();
			if($self){
				foreach ($maps as $key => $value) {
					if (strpos($value, '+') !== FALSE) {
						list($flag, $v) = explode('+', $value);
						$sets[] = "`$key` = `$key` + '$v'";
					} elseif (strpos($value, '-') !== FALSE) {
						list($flag, $v) = explode('-', $value);
						$v = intval($v);
						$sets[] = "`$key` = `$key` - '$v'";
					} else {
						$sets[] = "`$key` = '$value'";
					}
				}
			}else{
				foreach ($maps as $key => $value) {
					$sets[] = "`$key` = '$value'";
				}
			}

			$sql .= implode(',', $sets). ' ';

			$where = $this->parseWhere($where);
			if (!$where) {
				return false;
			}
			$sql .= $where;

			$this->sql = $sql;

			return $this->Exec($sql);
		}
	}


	/**
	 * Delete record(s)
	 * @param string => where condition for deletion
	 * @return false on failure or affected rows on success
	 */
	final public function Delete($where = '') {
		if(isset($this->options['where'])){
			$where = $this->options['where'];
			unset($this->options['where']);
		}

		$where = $this->parseWhere($where);

		if(!$where){
			return false;
		}

		$sql = 'DELETE FROM ' . $this->table . ' '. $where;

		$this->sql = $sql;

		return $this->Exec();
	}


	/**
	 * Execute UPDATE, DELETE SQL statements
	 * <br />Remark:  If error occurs and UAT is true, call raiseError() to display error and halt !
	 *
	 * @return result of execution
	 */
	final private function Exec() {
		$rows = self::$conn->exec($this->sql);
		$this->checkResult();

		return $rows;
	}


	private function getUnderscore($total = 10, $sub = 0) {
		$result = '';
		for($i=$sub; $i<= $total; $i++){
			$result .= '_';
		}
		return $result;
	}

	/**
	 * Check result for the last execution
	 *
	 * @param null
	 * @return null
	 */
	final private function checkResult() {
		if (self::$conn->errorCode() != $this->successCode) {
			$this->success = false;
			$error = self::$conn->errorInfo();
			$traceInfo = debug_backtrace();

			if (ENVIRONMENT == 'DEV') {
				Helper::raiseError($traceInfo, $error[2], $this->sql);
			} else {
				// Log error SQL and reason for debug
				$errorMsg = getClientIP(). ' | ' .date('Y-m-d H:i:s') .NL;
				$errorMsg .= 'SQL: '. $this->sql .NL;
				$errorMsg .= 'Error: '.$error[2]. NL;

				$title =  'LINE__________FUNCTION__________FILE______________________________________'.NL;
				$errorMsg .= $title;

				foreach ($traceInfo as $v) {
					$errorMsg .= $v['line'];
					$errorMsg .= $this->getUnderscore(10, strlen($v['line']));
					$errorMsg .= $v['function'];
					$errorMsg .= $this->getUnderscore(20, strlen($v['function']));
					$errorMsg .= $v['file'].NL;
				}

				file_put_contents($this->logFile, NL.$errorMsg, FILE_APPEND);

				if(defined('API_MODE')){
					eand(ERR_UNKNOWN);
				}

				return false;
			}
		}else{
			$this->success = true;
		}
	}


	/**
	 * Execute special SELECT SQL statement
	 *
	 * @param string  => SQL statement for execution
	 */
	final public function Query($sql) {
		$this->sql = $sql;
		if(!defined('ADMIN_MODE') && MEMCACHED == 1 && MEM_EXCEPTION != 1 && $this->cache){
			$key = md5($this->sql);
			$data = $GLOBALS['memcache']->get($key);

			if($data){
				return $data;
			}
		}

		$this->Execute();
		$this->checkResult();

		if($this->success){
			return $this->Fetch();
		}else{
			return false;
		}
	}

	/**
	 * 加载数据
	 * 
	 * @param type $param
	 * @param type $rule
	 * @param type $setDefault	是否装载默认值
	 * @param type $return		用于链式操作支持，默认为true不支持链式操作，仅返回数据，设置为false则返回自身并支持链式操作
	 * @return mixed			返回数据或类自身，默认返回数据
	 */
	final public function create($param = array(), $rule = array(), $setDefault = false, $return = true){
		if(empty($param) || !is_array($param)) {
			$this->error = '装载数据为空';
			return false;
		}

		//根据数据设置装载数据
		$data = $this->initParam($param, $rule, $setDefault);
		
		//自动验证
		$validateRule = array();
		if(!empty($rule)){//如果传入的rule不为空，则根据rule过滤验证内容，过滤掉rule未设置的
			foreach($rule as $item){
				if(isset($this->_validate[$item])){
					$validateRule[] = $this->_validate[$item];
				}
			}
		} else {
			$validateRule = $this->_validate;
		}
		$this->validate($data, $validateRule);
		
		if($this->_errLevel == 1){//如果存在致命错误，则返回false
			return false;
		}

		$this->data = $data;
		return $return ? $data : $this;
	}

	/**
	 * 筛选数据
	 *
	 * @param type $param
	 */
	final protected function initParam($param, $rule = array(), $setDefault = false, $filter = true) {
		$newParam = array();
		$default = $this->_fieldsSetting['_default'];
		if(!empty($rule) && is_array($rule)){
			if($setDefault){//如果已传入rule，且需要默认值，则将rule和default合并
				$rule = array_merge(array_keys($default), $rule);
			}
		} else {
			$rule = $this->_fieldsSetting;
		}
		foreach($rule as $key => $name) {
			if(in_array((string)$key, array('_pk', '_default'))){//内置的定义则跳过
				continue;
			}
			//如果传过来的值中存在
			if(isset($param[$name])) {
				$value = $filter ? filter(stripSQLChars(stripHTML(trim($param[$name]), true))) : $param[$name];
				$newParam[$name] = $value;
			} elseif($setDefault && isset($default[$name])) {
				$newParam[$name] = $default[$name];
			}
		}

		$this->param = $newParam;
		return $newParam;
	}

	final protected function validate($param = array(), $rule = array()){
		$rule = is_array($rule) && !empty($rule) ? $rule : $this->_validate;

		foreach($rule as $key => $value){
			//如果规则中存在参数的字段，则验证
			if(isset($param[$key])){
				//调用私有方法传递需要验证的字段以及当前规则进行验证
				$this->_validate($param[$key], $value);
			}
		}
		
		//返回自身，支持链式操作
		return $this;
	}
	
	private function _validate($value, $rule){
		//如果empty规则存在且为true，则验证$value是否为空
		if(isset($rule['empty']) && $rule['empty'] && empty($value)){//如果empty为true，且value为空，则存储错误
			$this->_setError($rule['name'] .'不能为空', 2);		//必填字段为空是无害错误，允许执行update
		}
		
		$len = strlen($value);
		if(isset($rule['max']) && $len > $rule['max']){//如果value长度大于max，则存储错误
			$this->_setError($rule['name'] .'长度最长限制为'. $rule['max'], 1);
		}
		
		if(isset($rule['min']) && $len < $rule['min']){//如果value长度小于min，且当前value不为空，则存储错误
			$this->_setError($rule['name'] .'长度最小限制为'. $rule['min'], 1);
		}
		
		if(isset($rule['reg']) && !preg_match($rule['reg'], $value)){//如果存在正则验证，且验证失败，则存储错误
			$this->_setError($rule['name'] .'格式不正确'. $rule['max'], 1);
		}
		
		if(isset($rule['function'])){//如果存在方法验证，则调用
			//判断方法是否存在，方法名规则：validate_方法名
			$function = 'validate_'. $rule['function'];
			if(method_exists($this, $function)){
				$this->$function($value);
			}
		}
	}
	
	private function _setError($msg, $level = 1){
		$this->_errLevel = $level;
		$this->_errMsg .= NL .$msg;
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
		
		if(isset($this->options['limit'])){
			$limit = $this->options['limit'];
			unset($this->options['limit']);
		}
		
		if(isset($this->options['join'])){
			$join = $this->options['join'];
			unset($this->options['join']);
		}
		
		return $this->parseSQL($fields, $where, $orderby, $limit, $join);
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
	final protected function parseSQL($field = '', $where = '', $orderby = '', $limit = '', $join = array()){
		$joinBool = !empty($join);
		$sqlfield = $this->parseField($field, $joinBool);
		$sqlwhere = $this->parseWhere($where, $joinBool);
		$sqlorderby = $this->parseOrder($orderby);
		$sqllimit = $this->parseLimit($limit);
		$sqltable = $this->parseTable($join);

		$sql = 'SELECT '. $sqlfield .' FROM '. $sqltable .' '. $sqlwhere .' '. $sqlorderby .' '. $sqllimit;
		$this->totalSql = 'SELECT COUNT(*) AS `count` FROM '. $sqltable .' '. $sqlwhere;

		return $sql;
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
				$operator = $where['_op'];
				unset($where['_op']);
				
				$search = array('eq', 'ne', 'gt', 'ge', 'lt', 'le');
				$operatorArr = array('=', '!=', '>', '>=', '<', '<=');
				
				if(!in_array($operator, $operatorArr)){//定义的运算符为转义，则替换
					$operator = str_replace($search, $operatorArr, strtolower($operator));
				}
			} else {//未定义默认为=
				$operator = '=';
			}

            foreach ($where as $key=>$val){
                $sqlwhere .= "( ";

				if(is_array($val)) {//$val为数组，调用_parseMultiWhere拼接
					$sqlwhere .= $this->_parseMultiWhere($key, $val, $alias);
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
	private function _parseMultiWhere($field, $value, $alias = ''){
		if(is_string($field)){//$val为数组且$key为字符串，则拼接为field IN ('1', '2', '3')的格式
			$valueList = implode('\',\'', $value);

			$sqlwhere = $this->parseKey($field, $alias) ." IN ('{$valueList}')";
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

		return $sqlorder;
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
		$sqltable = $this->table;
		if(isset($this->options['alias'])){
			$sqltable .= ' AS '. $this->options['alias'];
			unset($this->options['alias']);
		}
		if(!empty($join)){
			foreach($join as $item){
				$sqltable .= ' '. $item['type'] .' JOIN '. TB_PREFIX .$item['table'];
				if(!empty($item['on'])){
					$sqltable .= ' ON '. $item['on'];
				}
			}
		}
		
		return $sqltable;
	}

	// Return true if last operation is success or false on failure
	public function getOperationFlag(){
		return $this->success;
	}


	// ********* Execute transaction ********* //
	/**
	 * Start a transaction
	 *
	 * @param null
	 * @return true on success or false on failure
	 */
	public function beginTransaction() {
		self::$conn->beginTransaction();
	}


	/**
	 * Commit a transaction
	 *
	 * @param null
	 * @return true on success or false on failure
	 */
	public function Commit() {
		self::$conn->commit();
	}


	/**
	 * Rollback a transaction
	 *
	 * @param  null
	 * @return true on success or false on failure
	 */
	public function Rollback() {
		self::$conn->rollBack();
	}
	// *************** End ***************** //


	/**
	 * Close connection
	 *
	 * @param null
	 * @return null
	 */
	private function Close() {
		self::$conn = null;
	}


	/**
	 * Destructor
	 *
	 * @param null
	 * @return null
	 */
	function __destruct() {
		$this->Close();
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
//        if(is_string($value)) {
//            $value = '\''.$value.'\'';
//        }elseif(isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp'){
//            $value   =  $value[1];
//        }elseif(is_null($value)){
//            $value   =  'null';
//        } elseif(empty($value)) {
//			
//		}
        return $value = '\''.$value.'\'';;
    }
	
	
	//扩展方法：
	

	/**
	 * 根据id搜索某个字段，并返回该字段
	 *
	 * @param $field
	 * @param $id
	 * @return mixed
	 */
	public function getFieldByID($field, $id){
		$fields = array($field);
		$where = " WHERE `id`='$id' LIMIT 1";
		$data = $this->SelectOne($fields, $where);
		return $data[$field];
	}
}

?>