<?php
/**
 * File: upload
 * Created on : 2014-6-21, 1:33:50
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * �ϴ��ļ�
 * $fileObj �������ļ�
 * $path �ļ�·��
 * $type �ϴ����� 0:Ĭ�ϣ�ֻ�����ϴ�ͼƬ, 1:�����ϴ�txt,rar,doc,xls,ppt
 * return array('������Ϣ', 'error') / string �ļ���
 */
function hao_upload($fileObj, $path = 'default', $type = 0){
	$fullPath = DISCUZ_ROOT . './data/'. $path;
	if (! is_dir($fullPath)) {
			if (! @mkdir($fullPath, 0777, true)) {
				return array('�����ļ���ʧ��', 'error');
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
			return array('ֻ���ϴ�ͼƬ', 'error');
		}

		$upload->attach['target'] = $fullPath .'/' . $attach['attachment'];
		if (! $upload->error()) {
			$upload->save();
		}
		if ($upload->error()) {
			return array('�ϴ�����', 'error');
		} else {
			$filename = 'data/'. $path .'/' . $attach['attachment'];
		}
		return $filename;
}
?>