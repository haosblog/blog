<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2014-12-29 18:44:37
 * 
 * 前台页面中的基类，用于处理前台公用逻辑
 */

session_start();

class baseController extends controller {
	
	public function __construct($router = array()) {
		parent::__construct($router);
		
		if(empty($_SESSION['website'])){
			$host = $GLOBALS['host'];
			$info = M('domain')->alias('d')->join('website AS w', 'w.wsid=d.wsid')
					->field(array('w' => array('sitename', 'seotitle', 'keyword', 'description', 'tppath')))
					->where(array('d.domain' => $host))->selectOne();

			$_SESSION['website'] = $info;
		} else {
			$info = $_SESSION['website'];
		}
		
		$GLOBALS['tplPath'] = TPL_PATH . $info['tppath'] .'/';
	}
}