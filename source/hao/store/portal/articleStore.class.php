<?php

/**
 * File: articleStore.class.php
 * Created on : 2014-3-12, 23:44:36
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 文章数据类
 */
class articleStore extends store {


	public function getArticlesListByCatID($catid = 0, $page = 1, $pageCount = 20){
//		echo($catid);
//		die;
		global $_G;
		$portalcategory = &$_G['cache']['portalcategory'];
		$cat = $portalcategory[$catid];

		if(empty($cat)) {
			return 30101;
		}
		$categoryperm = getallowcategory($_G['uid']);
		if($cat['closed'] && !$_G['group']['allowdiy'] && !$categoryperm[$catid]['allowmanage']) {
			return 30102;
		}

		$cat = category_remake($catid);

		$m_articleTitle = $this->M('portal_article_title');
		$m_articleTitle->category_get_wheresql($cat);

		$cat['perpage'] = empty($cat['perpage']) ? 15 : $cat['perpage'];
		$cat['maxpages'] = empty($cat['maxpages']) ? 1000 : $cat['maxpages'];
		$pageCount = empty($pageCount) ? $cat['perpage'] : $pageCount;
		$page = empty($page) ? 1 : min($page, $cat['maxpages']);

		$wheresql = $m_articleTitle->category_get_wheresql($cat);
		return $m_articleTitle->categoryGetList($wheresql, $page, $pageCount);
	}

	public function getContent($aid){
		$data = $this->M('portal_article_title')->getDataByID($aid);
		$data['content'] = $this->M('portal_article_content')->getContentByID($aid);
		$data['catname'] = $this->M('portal_category')->getNameByID($data['catid']);

		return $data;
	}

	public function getCount($aid, &$article){
		//加载count表中的文章统计信息
		$articleCount = $this->M('portal_article_count')->getCountByID($aid);
		//如果不存在统计信息，则设置默认值
		if(!$articleCount){
			$articleCount = array(
				'viewnum'=>1,
				'commentnum' => 0
			);
		}
		$article = array_merge($articleCount, $article);
	}

	public function getCotent($aid, $page){
		$content = $contents = array();
		$multi = '';

		$content = $this->M('portal_article_content')->getContentByID($aid, $page);

//		if($article['contents'] && $article['showinnernav']) {
//			foreach(C::t('portal_article_content')->fetch_all($aid) as $value) {
//				$contents[] = $value;
//			}
//			if(empty($contents)) {
//				C::t('portal_article_content')->update($aid, array('showinnernav' => '0'));
//			}
//		}

		require_once libfile('function/blog');
		$content['content'] = blog_bbcode($content['content']);

		return $content;
	}

	private function _setListOrder($orderType = 'new'){
		$order = array();
		switch($orderType){
			case 'new':
				$order['a.`dateline`'] = 'desc';
			break;
			case 'hot';
				$order['ac.`viewnum`'] = 'desc';
			break;
			default :
				$order['a.`dateline`'] = 'desc';
		}

		return $order;
	}

}
