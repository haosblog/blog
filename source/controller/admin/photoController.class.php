<?php
/**
 * File: $(name)
 * Created on : 2013-12-15, 22:49:56
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 相册控制器
 */
class photoController extends baseController {

	public function __construct() {
		parent::__construct();
		$this->buffer['nav'] = 'photo';
	}

	public function index(){
		$limit = $this->getLimit(15);
		$aid = intval($_GET['aid']);
		$where = array("p.wsid='{$GLOBALS['wsid']}'");
		if($aid){
			$where['p.aid'] = $aid;
		}

		$this->buffer['list'] = M('photo')->field('p.*', 'a.name')->where($where)->order('p.`time` DESC')
				->alias('p')->join('album AS a', 'p.aid=a.aid')->limit($limit)->select();

		$this->display();
	}


	/**
	 * 上传照片
	 */
	public function upload(){
		$this->buffer['aidSelect'] = intval($_GET['aid']);

		$albumList = M('album')->field('aid', 'name')->where($this->where)->order('time DESC')->select();
		foreach($albumList as $item){
			$this->buffer['albumList'][$item['aid']] = $item['name'];
		}

		$this->display();
	}

	/**
	 * 图片上传处理事件
	 */
	public function action(){
		import('upload');
		$rule = array(
			'source' => array('explain' => '上传类型', 'rule' => 'null'),
			'aid' => array('explain' => '选择相册', 'rule' => 'null'),
			'title' => array('explain' => '图片标题', 'rule' => ''),
			'summary' => array('explain' => '图片说明', 'rule' => 'null'),
		);

		$param = $this->getParam($rule);
		
		if($this->error){
			$this->ajaxShow(0, $this->errormsg);
		}
		
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