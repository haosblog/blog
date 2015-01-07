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
		$page = isset($params['page']) ? max(1, intval($params['page'])) : 1;
		$orderby = isset($params['ordery']) ? addslashes($params['ordery']) : 'id DESC';
		$where = array();

		if(isset($params['wsid'])){// wsid参数被传入
			if(!empty($params['wsid'])){// 传入的wsid不为空（NULL，0，FALSE），则读取该网站下的文章，否则读取所有
				$where['wsid'] = $params['wsid'];
			}
		} else {
			$where['wsid'] = $GLOBALS['wsid'];
		}

		$data = SM($model)->page($page, $count)->order($orderby)->where($where)->select();
		$total = SM($model)->where($where)->count();
		if(!$data){
			return '';
		}

		$event->setProgressData('total', $total);
		$event->setCache($data);
	}

	$result = $event->loop();
	if(!$result){// 循环已结束，将分页信息写入模板变量
//		$page = new page();
	}

	return $content;
}