<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2015-1-15 18:36:06
 * 
 * 请在此处输入文件注释
 */

class introController extends baseController {
	
	public function index(){
		$this->buffer['info'] = M('intro')->where(array('wsid' => $this->wsid))->limit(1)->order('id DESC')->selectOne();
//		print_r($this->buffer);
		$this->buffer['info']['time'] = date('Y-m-d', $this->buffer['info']['time']);
		$this->display();
	}
}