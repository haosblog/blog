<?php
/**
 * File: articleController.class.php
 * Created on : 2014-11-17, 0:41:15
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class articleController extends controller {

	public function __construct($router = array()) {
		parent::__construct($router);
		$this->buffer['nav'] = 'article';
	}

	/**
	 * 编辑/新增文章
	 */
	public function edit(){
		$aid = intval($_GET['aid']);
		if($aid){
			$this->buffer['article'] = M('article')->getByAid($aid);
		}

		$this->buffer['category'] = M('category')->field('cid', 'catname')->select();

		$this->display();
	}

	public function action(){
		$maps = array();
		$maps['title'] = $title = htmlspecialchars($_POST['title'], 3);
		$maps['content_ori'] = $content = addslashes($_POST['content']);
		$maps['cid'] = $cid = intval($_POST['cid']);
		$maps['original'] = $original = intval($_POST['original']);
		$maps['status'] = intval($_POST['status']);
		if(!$original){// 文章不是原创，记录来源地址
			$maps['fromurl'] = $fromurl = addslashes($_POST['fromurl']);
		}

		if(!$title){
			$this->showmessage('标题不能为空');
		}

		if(!$cid){
			$this->showmessage('请选择文章分类');
		}

		if(!$content){
			$this->showmessage('请选择文章分类');
		}

		$parseObj = new editorEvent();
		$maps['content'] = $html = $parseObj->parseContent($content);

		M('article')->insert($maps);
	}

}
