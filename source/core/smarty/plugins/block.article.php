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
		$field = isset($params['field']) ? $params['field'] : array();
		$order = isset($params['order']) ? $params['order'] : 'wrtime DESC';
		$limit = isset($params['count']) ? intval($params['count']) : 10;
		$where = array();

		if(isset($params['cid'])){
			$where['cid'] = $params['cid'];
		}

		if(isset($params['wsid'])){// wsid参数被传入
			if(!empty($params['wsid'])){// 传入的wsid不为空（NULL，0，FALSE），则读取该网站下的文章，否则读取所有
				$where['wsid'] = $params['wsid'];
			}
		} else {
			$where['wsid'] = $GLOBALS['wsid'];
		}

		$data = M('view_article')->field($field)->where($where)->order($order)->limit($limit)->select();

		if(!$data){
			return '';
		}

		$GLOBALS['blockdata'][$dataindex] = $data;
	}


	$blockdata = $GLOBALS['blockdata'][$dataindex];
	if(isset($blockdata[$_index])){
		$smarty->assign('row', $blockdata[$_index]);
		$_index++;
		$repeat = true;
	} else {
		$_index = 0;
		$repeat = false;
	}

	$smarty->assign('_index', $_index);

	return $content;
}