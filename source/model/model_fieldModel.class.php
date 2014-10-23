<?php
/**
 * File: model_fieldModel.class.php
 * Created on : 2013-12-14, 23:33:00
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class model_fieldModel extends model {

	/**
	 * 获取需要在表单输入的字段
	 *
	 * @param type $mid
	 * @param type $all
	 * @return type
	 */
	public function getFormField($mid, $all = false){
		$field = array( 'fieldname', 'viewname', 'fieldtype', 'length', 'allownull', 'formtype',  'default', 'listview', 'allowhtml');
		if($all){
			$where = array('mid' => $mid);
		} else {
			$where = "`formtype` != 0 AND `mid`='$mid'";
		}

		$sql = $this->parseSQL($field, $where);
		$return = array();

		$query = $this->query($sql);
		while ($row = $this->fetch($query)){
			$return[$row['fieldname']] = $row;
		}

		return $return;
	}


	/**
	 * 返回要在列表中显示的字段
	 *
	 * @param int $mid	模型ID
	 * @return array(
	 *	fieldname => field列表
	 *	viewname => viewname列表
	 *
	 * )
	 */
	public function loadListField($mid){
		$field = array( 'fieldname', 'viewname', 'fieldtype');
		$where = array('mid' => $mid, 'listview' => 1);

		$fieldname= $viewname = $fieldtype = array();
		$sql = $this->parseSQL($field, $where);
		$query = $this->query($sql);

		while($row = $this->fetch($query)){
			$fieldname[] = $row['fieldname'];
			$viewname[] = $row['viewname'];
			$fieldtype[] = $row['fieldtype'];
		}

		$return = array($fieldname, $viewname, $fieldtype);

		return $return;
	}
}