<?php
/**
 * File: dynamicController.class.php
 * Created on : 2014-12-27, 22:14:20
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 动态模型的管理控制器
 */

class dynamicController extends baseController {


	/**
	 * 动态模型的数据列表页，也是管理首页
	 */
	public function index(){
		$page = $this->getPage();
		$this->buffer['mid'] = $mid = $this->_getMid();

		$modelObj = SM($mid);

		$this->buffer['list'] = $modelObj->page($page, 30)->order('id DESC')->select();
//		$this->buffer['field'] = M('model')->field('modname', 'tablename', 'classable')->getByMid($mid);
		$this->buffer['modname'] = M('model')->getFieldByMid($mid, 'modname');
		$this->buffer['fieldtype'] = M('model_field')->getFormField($mid, true);

		$this->display();
	}

	/**
	 * 发表内容
	 */
	public function edit(){
		if(isset($_POST['submit'])){
			$this->_editAction();
		}
		import('model', 'fun');
		$mid = $this->_getMid();

		$this->buffer['mid'] = $mid;
		$this->buffer['modname'] = M('model')->getFieldByMid($mid, 'modname');
		$this->buffer['list'] = M('model_field')->getFormField($mid);

		$this->display();
	}

	public function _editAction(){
		$mid = $this->_getMid();

		// 读取模型和字段的信息
		$modelData = M('model')->field('modname', 'tablename', 'classable')->getByMid($mid);
		$fieldList = M('model_field')->getFormField($mid, true);

		// 构造获取参数的规则
		$rule = $tmp = array();
		foreach($fieldList as $key => $item){
			if($item['formtype'] != 0){
				$rule[$key] = $this->_getFieldRule($item);
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

		// 调用getParam获取参数，并与tmp合并，tmp存储了一些字段的默认值
		$param = array_merge($tmp, $this->getParam($rule));

		$param['dateline'] = time();
		$param['wsid'] = $this->wsid;
		$a = M('mod_'. $modelData['tablename'])->insert($param);

		$this->_success('发布成功！');
	}


	/**
	 * 删除一条信息
	 */
	public function delete(){
		$mid = intval($_GET['mid']);
		$id = intval($_GET['id']);

		if(!$mid){
			$this->showmessage('模型类型不正确');
		}

		if(!$id){
			$this->showmessage('错误的处理');
		}

		$tablename = M('model')->getFieldByMid($mid, 'tablename');
		if(!$tablename){
			$this->showmessage('错误的模型');
		}

		M('mod_'. $tablename)->where(array('id' => $id))->delete();

		$this->_success('删除成功！');
	}

	/**
	 * 本控制器的成功信息输出，减少重复的代码
	 *
	 * @param type $msg
	 */
	private function _success($msg){
		$this->showmessage($msg, 1, '/admin/dynamic?mid='. $mid);
	}


	private function _getFieldRule($item = array()){
		$ruleArr = array();
		if($item['allownull'] == 1){ $ruleArr[] = 'null'; }

		if($item['length'] > 0){ $ruleArr[] = 'max:'. $item['length']; }

		$return = array(
			'explain' => $item['viewname'],
			'rule' => implode('|', $ruleArr),
		);

//		if($item['allowhtml'] == 1){
//			$return['html'] = true;
//		}

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
