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
		import('page');

		$cid = $this->buffer['cid'] = intval($_GET['cid']);
		$keyword = htmlspecialchars($_GET['q']);
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
			$keyword = str_replace('+', ' ', $keyword);

			if(strpos($keyword, ' ') !== false){
				$keywordArr = explode(' ', $keyword);
				$tmp['_logic'] = 'OR';
				$tmp['_op'] = 'll';

				foreach($keywordArr as $item){
					$tmp[] = array(
						'title' => $item,
						'content' => $item,
						'_op' => 'l',
						'_logic' => 'OR'
					);
				}
			} else {
				$tmp = array(
					'title' => $keyword,
					'content' => $keyword,
					'_op' => 'l',
					'_logic' => 'OR'
				);
			}
			$where[] = $tmp;
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

		$m = M('view_article');
		$this->buffer['category'] = M('category')->field('cid', 'catname', 'count')->where(array('mid' => 0, 'wsid' => $wsid))->select();
		$this->buffer['article'] = $m->field('aid', 'cid', 'catname', 'title', 'original', 'viewcount', 'repostcount', 'wrtime', 'wrtime', 'chtime')
				->where($where)->page($page, 20)->order($orderby)->select();
		$total = M('article')->where($where)->count();

		$pageObj = new page($page, 20, $total);

//		echo($m->getLastSQL());die;

		$this->display();
	}

	public function read(){
		$aid = intval($_GET['aid']);
		$page = $this->getPage();

		if(!$aid){
			$this->display('404');
		}

		// 读取当前页评论
		$this->buffer['comment'] = M('comment')->where(array('type' => 1, 'fid' => $aid, 'wsid' => $this->wsid))->page($page, 5)->select();
		$this->buffer['nextpage'] = $page + 1;

		if(!isset($_GET['comment'])){// GET未传入comment参数，则读取文章信息
			$m_article = M('article');
			if(!isset($_SESSION['read'][$aid])){// session中没有标记文章已读，则更新点击数
				$_SESSION['read'][$aid] = 1;
				$m_article->where(array('aid' => $aid))->setInc('viewcount');
			}
			$where = array('aid' => $aid, 'wsid' => $GLOBALS['wsid']);

			$this->buffer['article'] = $article = M('article')->field('aid', 'cid', 'title', 'content', 'original', 'fromurl', 'viewcount', 'repostcount', 'wrtime')->where($where)->selectOne();
			if(!$article){
				$this->display('404');
			}
			$this->title = $article['title'];

			$this->display();
		} else {// GET传入了comment参数，则表示ajax读取评论信息，仅输出评论的模板
			$this->display();
		}
	}
}
