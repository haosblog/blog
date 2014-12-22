<?php

/**
 * File: modelAction.class.php
 * Created on : 2013-12-8, 19:59:02
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 管理系统模型
 */

class modelController extends baseController {

	/**
	 * 模型管理列表
	 */
	public function index(){
		$limit = $this->getLimit();
		$m_model = M('model');
		$this->buffer['list'] = $m_model->limit($limit)->select();

		$this->display();
	}

	/**
	 * 编辑/新增模型
	 */
	public function edit(){
		$mid = intval($_GET['mid']);

		if($mid){
			$this->buffer['info'] =M('model')->getByMid($mid);
			$this->buffer['field'] =M('model_field')->getByMid($mid);
		}
		
		$this->display();
	}

	public function import(){
		$this->display();
	}


	public function importAction(){
		import('upload');

		$upload = new upload('modelJson', 'tmp');
		$upload->setAllowType('application/octet-stream');

		$jsonPath = HAO_ROOT .$upload->save();
		$jsonData = file_get_contents($jsonPath);

		$modelArr = json_decode($jsonData, true);
		M('model')->import($modelArr);

		$this->showmessage('导入成功！');
	}


	/**
	 * 发表内容
	 */
	public function post(){
		import('model', 'fun');
		$mid = $this->_getMid();

		$this->buffer['mid'] = $mid;
		$this->buffer['modname'] = M('model')->getModNameById($mid);
		$this->buffer['list'] = M('model_field')->getFormField($mid);

		$this->display();
	}


	public function postAction(){
		$mid = $this->_getMid();

		$modelField = array('modname', 'tablename', 'classable');
		$modelWhere = array('mid' => $mid);
		$modelData = M('model')->selectOne($modelField, $modelWhere);

		$fieldList = M('model_field')->getFormField($mid, true);
		$rule = array();

		foreach($fieldList as $key => $item){
			if($item['formtype'] != 0){
				$rule[$key] = $this->_getFieldRule($item);
			} elseif($item['fieldtype'] == 11){
				$tmp[$key] = time();
			} else {
				$tmp[$key] = $item['default'];
			}
		}

		if($modelData['classable']){
			$rule['cid'] =array(
				'explain' => $modelData['modname'] .'栏目',
				'rule' => 'number',
			);
		}

		$param = $this->getParam($rule);
		$param = array_merge($param, $tmp);

		$m_modelSystem = SM($mid);
		$m_modelSystem->insertData($param);

		$this->showmessage('发布成功！');
	}


	public function contentlist(){
		$page = $this->getPage();
		$mid = $this->_getMid();

		$modelObj = SM($mid);

		$data = $modelObj->loadList($page, 30);
		$this->buffer['field'] = $data['field'];
		$this->buffer['fieldtype'] = $data['fieldtype'];
		$this->buffer['list'] = $data['list'];

		$this->display();
	}


	private function _getFieldRule($item = array()){
		$ruleArr = array();
		if($item['allownull'] == 1){ $ruleArr[] = 'null'; }

		if($item['length'] > 0){ $ruleArr[] = 'max:'. $item['length']; }

		$return = array(
			'explain' => $item['viewname'],
			'rule' => implode('|', $ruleArr),
		);

		if($item['allowhtml'] == 1){
			$return['html'] = true;
		}

		return $return;
	}


	/**
	 * 获取GET传递来的MID
	 */
	private function _getMid(){
		$mid = intval($_GET['mid']);

		if($mid < 1){
			$this->showmessage('模型选择错误！');
		}

		return $mid;
	}
}