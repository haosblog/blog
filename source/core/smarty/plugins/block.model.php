<?php
/**
 * File: block.model.php
 * Functionality: text
 * Author: Hao
 * Date: 2013-12-26
 */

function smarty_block_model($params, $content, &$smarty, &$repeat){
	extract($params);

	if(!isset($model)){
		return '';
	}

	if(!isset($item)){
		$item = 'row';
	}

	if(!isset($count)){
		$count = 10;
	}

	if(!isset($orderby)){
		$orderby = 'id';
	} else {
		$orderby = addslashes($orderby);
	}

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

		$data = $modelObj->loadList(1, $count, $orderby);
		if(!$data){
			return '';
		}

		$GLOBALS['blockdata'][$dataindex] = $data['list'];
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