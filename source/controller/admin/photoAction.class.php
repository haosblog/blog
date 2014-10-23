<?php
/**
 * File: $(name)
 * Created on : 2013-12-15, 22:49:56
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 相册控制器
 */
class photoAction extends adminBase {
	public function index(){
		$page = $this->getPage();
		$aid = intval($_GET['aid']);
		$this->buffer['list'] = M('photo')->loadList($aid, $page, 30);

		$this->display();
	}

	public function album(){
		$page = $this->getPage();
		$this->buffer['list'] = M('album')->loadList($page, 10);

		$this->display();
	}


	/**
	 * 上传相册
	 */
	public function upload(){
		$this->buffer['aidSelect'] = intval($_GET['aid']);
		$this->buffer['albumList'] = M('album')->loadSelectList();

		$this->display();
	}

	public function uploadAction(){
		import('upload');
		$rule = array(
			'source' => array('explain' => '上传类型', 'rule' => 'null'),
			'aid' => array('explain' => '选择相册', 'rule' => 'null'),
			'title' => array('explain' => '图片标题', 'rule' => ''),
			'summary' => array('explain' => '图片说明', 'rule' => 'null'),
		);

		$param = $this->getParam($rule, 'post', true);
		$param['wsid'] = $GLOBALS['wsid'];
		$param['time'] = time();

		if(empty($param['source']) || $param['source'] < 1){
			$aid = $param['aid'] = intval($param['aid']);
			$albumName = M('album')->getNameById($aid);
			$sourceText = '相册：'. $albumName;

			$param['source'] = 0;
			$param['sourcetext'] = $sourceText;
			$param['sourceurl'] = '/album/'. $aid;
		}

		$upload = new upload('upimg');
		$param['path'] = $upload->save();

		if(!empty($upload->error)){
			$error = '图片上传出错：'. $upload->error;
			$this->ajaxShow(0, $error);
		}

		if($param['source'] == 0){
			$aid = $param['aid'];
		}

		M('photo')->insert($param);

		$this->ajaxShow(1, '上传成功！');
	}
}