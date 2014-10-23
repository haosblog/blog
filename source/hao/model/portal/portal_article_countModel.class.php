<?php
/**
 * File: portal_article_countModel.class.php
 * Created on : 2014-5-2, 23:52:20
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class portal_article_countModel extends model{

	public $tableObj = null;

	public function getCountByID($aid, $update = true){
		$articleCount = $this->tableObj->fetch($aid);

		//判断是否存在count信息，存在则更新viewnum
		if($articleCount) {
			if($update){
				$this->tableObj->increase($aid, array('viewnum'=>1));
			}
		}

		return $articleCount;
	}
}