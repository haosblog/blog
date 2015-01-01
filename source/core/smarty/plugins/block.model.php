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

	$event = new blockEvent($params, $content, $smarty, $repeat);

	if(!$event->checkCache()){
		$count = isset($params['count']) ? $params['count'] : 10;
		$orderby = isset($params['ordery']) ? addslashes($params['ordery']) : 'id DESC';

		$data = SM($model)->limit($count)->order($orderby)->select();
		if(!$data){
			return '';
		}

		$event->setCache($data);
	}

	$event->loop();

	return $content;
}