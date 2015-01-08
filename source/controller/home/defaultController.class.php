<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2014-12-29 18:43:15
 *
 * 默认的控制器，控制器不存在时调用
 */

class defaultController extends baseController {

	public function index(){
		if(strpos($GLOBALS['controller'], '.php') !== false || strpos($GLOBALS['controller'], '.asp') !== false){// 旧版本的链接，跳转
			$this->_old();
		} else {// 非旧版链接，直接输出相关模板，模板不存在则抛出404
			if($this->checkTpl()){
				$this->display();
			} else {
				$this->display('error/404');
			}
		}
	}
	
	private function _old(){
		
	}
}
