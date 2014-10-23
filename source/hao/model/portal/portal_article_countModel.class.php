<?php
/**
 * File: portal_article_countModel.class.php
 * Created on : 2014-5-2, 23:52:20
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * text
 */

class portal_article_countModel extends model{

	public $tableObj = null;

	public function getCountByID($aid, $update = true){
		$articleCount = $this->tableObj->fetch($aid);

		//�ж��Ƿ����count��Ϣ�����������viewnum
		if($articleCount) {
			if($update){
				$this->tableObj->increase($aid, array('viewnum'=>1));
			}
		}

		return $articleCount;
	}
}