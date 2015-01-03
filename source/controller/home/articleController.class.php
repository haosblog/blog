<?php
/**
 * File: articleController.class.php
 * Created on : 2014-12-3, 9:13:03
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class articleController extends baseController {

	private $c = '';

	public function index(){
		$cid = $this->buffer['cid'] = intval($_GET['cid']);
		$keyword = htmlspecialchars($_GET['keyword']);
		$page = $this->getPage();
		$order = $_GET['order'];
		$wsid = $GLOBALS['wsid'];

		$where = array('wsid' => $wsid);

		if($cid){
			$where['cid'] = $cid;

			$catSEO = M('category')->field('catname', 'title', 'keyword', 'description')->getByCid($cid);
			$this->title = !empty($catSEO['title']) ? $catSEO['title'] : $catSEO['catname'] .' 日志列表';
			$this->keyword = $catSEO['keyword'];
			$this->description = $catSEO['description'];
		}

		if($keyword){
			$where[] = array(
				'title' => $keyword,
				'cotent' => $keyword,
				'_op' => 'll'
			);
		}

		switch ($order){
			case 'chd':// 修改时间倒序
				$orderby = 'chtime DESC';
				break;

			case 'hot': // 评论最多
				$orderby = 'repostcount DESC';
				break;

			default :// 默认发布时间倒序
				$orderby = 'wrtime DESC';
				break;
		}

		$this->buffer['category'] = M('category')->field('cid', 'catname', 'count')->where(array('mid' => 0, 'wsid' => $wsid))->select();
		$this->buffer['article'] = M('view_article')->where($where)->page($page, 20)->order($orderby)->select();

		$this->display();
	}

	public function read(){
		$aid = intval($_GET['aid']);

		if(!$aid){
			$this->display('error');
		}

		$where = array('aid' => $aid, 'wsid' => $GLOBALS['wsid']);

		$this->buffer['article'] = $article = M('article')->field('cid', 'title', 'content', 'original', 'fromurl', 'viewcount', 'repostcount', 'wrtime')->where($where)->selectOne();
		$this->title = $article['title'];

		$this->display();
	}
}
