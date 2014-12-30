<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2014-12-29 18:44:37
 * 
 * 前台页面中的基类，用于处理前台公用逻辑
 */

class baseController extends controller {
	
	public function __construct($router = array()) {
		parent::__construct($router);
		
		$host = $GLOBALS['host'];
		
		$GLOBALS['tplPath'] = $tplPath;
	}
}