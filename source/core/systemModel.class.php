<?php
/**
 * File: systemModel
 * Created on : 2013-12-14, 23:33:00
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 系统模型
 */

class systemModel extends model{

	public $m_model = null;
	public $m_model_field = null;
	public $model = array();
	public $mid;

	public function __construct($mid) {
		$this->m_model = M('model');
		$this->m_model_field = M('model_field');

		if(is_numeric($mid)){
			$where = array('mid' => $mid);
		} else {
			//当mid不为数字时，则将mid作为modelname搜索mid
			//清除可能造成注入的内容
			$tablename = addslashes($mid);
			$where = array('tablename' => $tablename);
		}

		$modData = $this->m_model->where($where)->selectOne();
		if(!$modData){
			die('错误的模型调用！');
		}

		$this->model = $modData;
		$this->mid = $modData['mid'];

		$modelname = 'mod_'. $modData['tablename'];
		parent::__construct($modelname);
	}


	public function insertData($data, $wsid = 0){
		if(!$wsid){
			$wsid = $GLOBALS['wsid'];
		}

		$wsid = intval($wsid);
		$data['wsid'] = $wsid;

		$this->insert($data);
	}

	public function loadList($page, $pagecount, $orderby = '', $withclass = true, $wsid = false){
		$wsid = $this->getWsid($wsid);
		$limit = $this->getLimit($page, $pagecount);

		list($field, $fieldview, $fieldtype) = $this->m_model_field->loadListField($this->mid);
		if($this->model['classable'] && $withclass){
			$list = $this->_loadListWithCategory($field, $limit, $orderby, $wsid);
		} else {
			$list = $this->_loadList($field, $limit, $orderby, $wsid);
		}
		return array(
			'field' => $fieldview,
			'list' => $list,
			'fieldtype' => $fieldtype
		);
	}


	private function _loadListWithCategory($field, $limit, $orderby = '', $wsid = false){
			$fields = array(
				'a' => $field,
				'c' => array('catname', 'cid')
			);
			$tables = array('a' => $this->modelname, 'c' => 'category');
			$where = array('a.`cid`' => 'c.`cid`');
			if($wsid != 0){
				$where['a.`wsid`'] = $wsid;
			}

			if(!empty($orderby)){
				$orderby = 'a.`'. $orderby .'` DESC';
			}

			$list = $this->selectJoin($tables, $fields, $where, $orderby, $limit);
			return $list;
	}

	private function _loadList($field, $limit, $orderby = '', $wsid = false){
		$where = $wsid == 0 ? '' : array('wsid' => $wsid);

		$list = $this->select($field, $where, $orderby, $limit);
		return $list;
	}
}