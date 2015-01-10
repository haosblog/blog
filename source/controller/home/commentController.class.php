<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2015-1-6 18:42:44
 *
 * 请在此处输入文件注释
 */


class commentController extends baseController {

	public function index(){
		$page = $this->getPage();
		$where = array(
			'wsid' => $this->wsid,
			'type' => 0
		);

		$this->buffer['list'] = M('comment')->where($where)->select();

		$this->display();
	}
	
	public function action(){
		M();
	}
}