<?php
/**
 * File: block.photo.php
 * Functionality:
 * Author: hao
 * Date: 2014-1-3
 * Remark:
 */

function smarty_block_album($params, $content, &$smarty, &$repeat){
	extract($params);

	$count = intval($count);

	if(!wsid){
		$wsid = false;
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
		$m_album = M('album');
		$data = $m_album->loadList(1, $count, $wsid);
		if(!$data){
			return '';
		}

		$GLOBALS['blockdata'][$dataindex] = $data;
	}

	$blockdata = $GLOBALS['blockdata'][$dataindex];
	$row = $blockdata[$_index];
	if(isset($blockdata[$_index])){
		$row['cover'] = '/data/upload/cover/'. $row['aid'] .'.jpg';
		$smarty->assign('row', $row);
		$_index++;

		$repeat = true;
	} else {
		$_index = 0;
		$repeat = false;
	}

	$smarty->assign('_index', $_index);

	return $content;
}