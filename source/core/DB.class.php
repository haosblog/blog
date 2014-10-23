<?php
/**
 * File: DB.php
 * Functionality: 
 * Author: hao
 * Date: 2014-8-8 12:00:40
 */

class DB {
	
	static private $db;
	
	static public function init(){
		if(self::$db != null){
			return self::$db;
		}

		self::$db = new mysql();
		return self::$db;
	}

	/**
     * 关闭数据库
     * @access public
     * @return void
     */
    static public function close(){
		self::$db->close();
	}

	/**
	 * 释放查询结果
	 * @access public
	 */
	static public function free(){
		self::$db->free();
	}

	static public function error(){
		self::$db->error();
	}

	/**
	 * 给表名添加前缀
	 *
	 * @param type $table
	 * @return type
	 */
	static public function table($table){
		self::$db->table($table);
	}

	/**
	 * 执行查询 返回数据集
	 * @access public
	 * @param string $str  sql指令
	 * @return mixed
	 */
	static public function query($sql){
		self::$db->query($sql);
	}

	static public function fetch($result){
		self::$db->fetch($result);
	}

	/**
	 *
	 * @param type $sql
	 * @return type
	 */
	static public function fetch_first($sql){
		self::$db->fetch_first($sql);
	}

	static public function fetch_all($sql){
		self::$db->fetch_all($sql);
	}

	static public function insert($table, $data, $getid = false){
		self::$db->insert($table, $data, $getid = false);
	}

	static public function update($table, $data, $where = ''){
		self::$db->update($table, $data, $where = '');
	}

	/**
	 * 获取
	 */
	static public function insert_id(){		
		self::$db->insert_id();
	}

}
