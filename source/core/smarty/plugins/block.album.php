<?php
/**
 * File: block.photo.php
 * Functionality:
 * Author: hao
 * Date: 2014-1-3
 * Remark:
 */

function smarty_block_album($params, $content, &$smarty, &$repeat){
	$evnet = new blockEvent($params, $content, $smarty, $repeat);
	if(!$evnet->checkCache()){
		$field = isset($params['field']) ? $params['field'] : array();
		$order = isset($params['order']) ? $params['order'] : 'time DESC';
		$limit = isset($params['count']) ? intval($params['count']) : 10;
		$where = array();

		if(isset($params['wsid'])){// wsid参数被传入
			if(!empty($params['wsid'])){// 传入的wsid不为空（NULL，0，FALSE），则读取该网站下的文章，否则读取所有
				$where['wsid'] = $params['wsid'];
			}
		} else {
			$where['wsid'] = $GLOBALS['wsid'];
		}

		$data = M('album')->field($field)->where($where)->order($order)->limit($limit)->select();
		if(!$data){
			return '';
		}

		$evnet->setCache($data);
	}

	$row = $evnet->getRow();
	$row['cover'] = '/data/upload/cover/'. $row['aid'] .'.jpg';
	$evnet->setRow($row);
	$evnet->loop();

	return $content;
}