<?php
/**
 * File: upload
 * Created on : 2014-6-21, 1:33:50
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 上传文件
 * $fileObj 表单传递文件
 * $path 文件路径
 * $type 上传类型 0:默认，只允许上传图片, 1:允许上传txt,rar,doc,xls,ppt
 * return array('错误信息', 'error') / string 文件名
 */
function hao_upload($fileObj, $path = 'default', $type = 0){
	$fullPath = DISCUZ_ROOT . './data/'. $path;
	if (! is_dir($fullPath)) {
			if (! @mkdir($fullPath, 0777, true)) {
				return array('创建文件夹失败', 'error');
			}
		}

		$allow = array(
			0 => array(
				'image/pjpeg',
				'image/jpeg',
				'image/png',
				'image/gif',
				'image/bmp'
			),
			1 => array(
				'image/pjpeg',
				'image/jpeg',
				'image/png',
				'image/gif',
				'image/bmp'
			)
		);
		//require_once libfile('class/upload');

		$upload = new discuz_upload();
		$upload->init($fileObj, 'temp');
		$attach = $upload->attach;
		if (! in_array($attach['type'], $allow[$type])) {
			return array('只能上传图片', 'error');
		}

		$upload->attach['target'] = $fullPath .'/' . $attach['attachment'];
		if (! $upload->error()) {
			$upload->save();
		}
		if ($upload->error()) {
			return array('上传错误', 'error');
		} else {
			$filename = 'data/'. $path .'/' . $attach['attachment'];
		}
		return $filename;
}
?>