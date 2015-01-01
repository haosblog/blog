<?php
/**
 * File: block.model.php
 * Functionality: text
 * Author: Hao
 * Date: 2013-12-26
 */

function smarty_block_model($params, $content, &$smarty, &$repeat){
	if(!isset($params['model'])){
		return '';
	} else {
		$model = $params['model'];
	}

	$item = isset($params['item']) ? $params['item'] : 'row';
	$count = isset($params['count']) ? $params['count'] : 10;
	$orderby = isset($params['ordery']) ? addslashes($params['ordery']) : 'id DESC';

	// 检测smarty是否存在get_template_vars方法，用于兼容旧版本smarty
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
		$modelObj = SM($model);

		$data = $modelObj->limit($count)->order($orderby)->select();
		if(!$data){
			return '';
		}

		$GLOBALS['blockdata'][$dataindex] = $data;
	}

	$blockdata = $GLOBALS['blockdata'][$dataindex];
	if(isset($blockdata[$_index])){
		$smarty->assign($item, $blockdata[$_index]);
		$_index++;

		$repeat = true;
	} else {
		$_index = 0;
		$repeat = false;
	}

	$smarty->assign('_index', $_index);

	return $content;
}