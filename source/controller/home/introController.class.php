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
		$this->buffer['info'] = M('intro')->where(array('wsid' => $this->wsid))->limit(1)->order('id DESC');
		$this->display();
	}
}
