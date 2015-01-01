<?php
/**
 * File: blockEvent.class.php
 * Created on : 2015-1-2, 2:20:21
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 块函数调用类
 */

class blockEvent {


	// 数据厉遍传递给模板的数据的变量名
	private $_item;
	// 用于缓存数据的索引
	private $_dataindex;
	// 数据缓存
	static private $_cache;
	// 循环过程中的索引
	static private $_loopIndex = 0;

	private $content, $smarty, $repeat;

	public function __construct($params, $content, &$smarty, &$repeat) {
		$this->_dataindex = $this->getDataIndex($params);
		$this->_item = isset($params['item']) ? $params['item'] : 'row';

		$this->smarty = &$smarty;
		$this->repeat = &$repeat;
		$this->content = $content;

	}

	public function checkCache(){
		return isset(self::$_cache[$this->_dataindex]);
	}

	public function setCache($data){
		self::$_cache[$this->_dataindex] = $data;

		return $this;
	}

	public function loop(){
		$blockdata = self::$_cache[$this->_dataindex];
		if(isset($blockdata[self::$_loopIndex])){
			$this->smarty->assign($this->_item, $blockdata[self::$loopIndex]);
			self::$loopIndex++;

			$this->repeat = true;
		} else {
			self::$loopIndex = 0;
			$this->repeat = false;
		}


		$this->smarty->assign('_index', self::$loopIndex);
	}

	public function getDataIndex($params){
		return substr(md5(__FUNCTION__ . md5(serialize($params))), 0, 16);
	}
}