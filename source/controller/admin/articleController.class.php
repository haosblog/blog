<?php
/**
 * File: articleController.class.php
 * Created on : 2014-11-17, 0:41:15
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 后台文章管理
 */

class articleController extends baseController {

	public function __construct($router = array()) {
		parent::__construct($router);
		$this->buffer['nav'] = 'article';
	}

	public function index(){
		$where = array('wsid' => $this->wsid);
		$cid = intval($_GET['cid']);

		if($cid){
			$where['cid'] = $cid;
		}

		$this->buffer['list'] = M('view_article')->where($where)->order('wrtime DESC')->select();

		$this->display();
	}

	/**
	 * 编辑/新增文章
	 */
	public function edit(){
		$aid = intval($_GET['aid']);
		if($aid){
			$this->buffer['info'] = M('article')->getByAid($aid);
		}

		$this->buffer['category'] = M('category')->field('cid', 'catname')->where(array('wsid' => $this->wsid))->select();

		$this->display();
	}

	public function action(){
		$maps = array();
		$aid = intval($_POST['aid']);
		$maps['title'] = $title = htmlspecialchars($_POST['title'], 3);
		$maps['content_ori'] = $content = addslashes($_POST['content']);
		$maps['cid'] = $cid = intval($_POST['cid']);
		$maps['original'] = $original = intval($_POST['original']);
		$maps['status'] = intval($_POST['status']);
		$maps['wsid'] = $this->wsid;
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

		$m_article = M('article');
		$m_category = M('category');
		if($aid){
			$maps['chtime'] = time();
			// 获取文章原所属栏目，用于对比
			$oldCid = $m_article->getFieldByAid($aid, 'cid');
			if($oldCid != $cid){// 本文章栏目已变更
				$m_category->where(array('cid' => $oldCid))->setDec('count', 1);
				$m_category->where(array('cid' => $cid))->setInc('count', 1);
			}
			$m_article->where(array('aid' => $aid))->update($maps);
		} else {
			$maps['wrtime'] = $maps['chtime'] = $maps['retime'] = time();
			$m_category->where(array('cid' => $cid))->setInc('count', 1);
			$m_article->insert($maps);
		}

		$this->showmessage('文章发表成功！', 1, '/admin/article');
	}

}
