<?php
/**
 * File: model.fun.php
 * Created on : 2013-12-24, 1:00:42
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 模型所需的函数
 */


/**
 * 根据传递的类型，生成模型表单
 *
 * @param type $type
 */
function parseModelForm($modelData){
	$type = $modelData['formtype'];
	$rule = '';

	if($modelData['allownull'] != 1){
		$rule = 'required';
	}

	switch ($type){
		case 1:
			$form = '<input type="text" id="'. $modelData['fieldname'] .'" name="'. $modelData['fieldname'] .'" '.
				$rule .' placeholder="'. $modelData['viewname'] .'" class="form-control" />';
		break;

		case 2:
			$form = '<input type="password" id="'. $modelData['fieldname'] .'" name="'. $modelData['fieldname'] .'" '.
				$rule .' placeholder="'. $modelData['viewname'] .'" class="form-control" />';
		break;

		case 3:
		case 4:
		break;

		case 5:
			$form = '<input type="checkbox" id="'. $modelData['fieldname'] .'" name="'. $modelData['fieldname'] .'" '.
				$rule .' placeholder="'. $modelData['viewname'] .'" />';
		break;

		case 6:
			$form = '<textare id="'. $modelData['fieldname'] .'" name="'. $modelData['fieldname'] .'" '.
				$rule .' placeholder="'. $modelData['viewname'] .'" class="form-control"></textare>';
		break;

		case 7:
			$form = '<textarea id="'. $modelData['fieldname'] .'" name="'. $modelData['fieldname'] .'" '.
				' placeholder="'. $modelData['viewname'] .'" class="form-control kindeditor"></textarea>';
		break;

		default :
			//TODO 逻辑需要重新设计
		break;
	}

	echo($form);
}