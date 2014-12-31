<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2014-12-30 18:36:16
 * 
 * smarty 文章函数块
 */

function smarty_block_article($params, $content, &$smarty, &$repeat){
	if(method_exists($smarty, 'get_template_vars')){
		$_index = $smarty->get_template_vars('_index');
	} else {
		$_index = $smarty->getVariable('_index')->value;
	}

	if(!$_index){
		$_index = 0;
	}

	$dataindex = substr(md5(__FUNCTION__ . md5(serialize($params))), 0, 16);

	if(!isset($GLOBALS['blockdata'][$dataindex])){
		$m_article = M('article');
		$field = isset($params['field']) ? $params['field'] : array();
		$order = isset($params['order']) ? $params['order'] : 'wrtime DESC';
		$limit = isset($params['count']) ? intval($params['count']) : 10;
		$where = array();
		
		if(isset($params['cid'])){
			$where['cid'] = $params['cid'];
		}
		
		
		$data = $m_article->field($field)->where($where)->order($order)->limit($limit)->select();
		if(!$data){
			return '';
		}

		$GLOBALS['blockdata'][$dataindex] = $data;
	}

	$blockdata = $GLOBALS['blockdata'][$dataindex];
	$row = $blockdata[$_index];
	if(isset($blockdata[$_index])){
		$_index++;

		$repeat = true;
	} else {
		$_index = 0;
		$repeat = false;
	}

	$smarty->assign('_index', $_index);

	return $content;
}