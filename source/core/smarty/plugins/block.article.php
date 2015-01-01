<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2014-12-30 18:36:16
 *
 * smarty 文章函数块
 */

function smarty_block_article($params, $content, &$smarty, &$repeat){
	$event = new blockEvent($params, $content, $smarty, $repeat);

	if(!$event->checkCache()){
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

		$event->setCache($data);
	}

	$event->loop();

	return $content;
}