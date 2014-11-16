<?php
/**
 * File: $(name)
 * Created on : 2013-12-17, 22:24:28
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 上传类
 */

class upload {
	var $allow, $savePath, $error, $fileObj, $filename;
	var $maxsize = 2000;	//默认最大限制为2M

	function __construct($formName, $savePath = '') {
		$this->fileObj = $_FILES[$formName];
		if ($this->fileObj["error"] > 0){
			$this->error = $_FILES["file"]["error"];
		}

		$this->filename = $this->fileObj['name'];

		$this->savePath = '/data/upload/';
		if(empty($savePath)){
			//默认情况下图片上传目录每月新增一个文件夹进行文件归档
			$defaultPath = 'img/'. date('Ym');
			$this->savePath .= $defaultPath;
		} else {
			$this->savePath .= $savePath;
		}

		//默认为上传图片
		$this->setAllowType(1);
	}


	/**
	 * 设置允许的文件类型
	 *
	 * @param type $type 文件类型，1：图片类型。为数组则为可允许的文件类型列表
	 * @return boolean
	 */
	public function setAllowType($type = '1'){
		switch($type){
			case 1:
				$allow = array(
					'image/pjpeg',
					'image/jpeg',
					'image/png',
					'image/gif'
				);
			break;
			default :
				if(is_array($type)){
					$allow = $type;
				} else {
					$allow = array($type);
				}
		}

		$this->allow = $allow;
	}

	public function setMaxSize($maxSize = 2000){
		$this->maxsize = $maxSize;
	}

	public function save(){
		if($this->fileObj['size'] > $this->maxsize * 1024){
			$this->error .= '文件大小超出范围';
		}

		if(!in_array($this->fileObj['type'], $this->allow)){
			$this->error .= '不被允许的文件类型';
		}

		if(!$this->fileObj['name']){
			$this->error .= '请选择上传文件！';
		}

		if(empty($this->error)){
			$staticPath = HAO_ROOT . $this->savePath;
			if (!is_dir($staticPath)) {
				if (!mkdir($staticPath, 0777, true)) {
					return array('创建文件夹失败', 'error');
				}
			}

			$filename = $this->getSaveName($this->fileObj['name']);
			$filepath = $this->savePath .'/'. $filename;
			$savepath = HAO_ROOT . $filepath;

			move_uploaded_file($this->fileObj["tmp_name"], $savepath);

			return $filepath;
		}

		return $this->error;
	}


	private function getSaveName($oldname){
		$start = strripos($oldname, '.');
		$pointName = substr($oldname, $start);

		$newName = date('YmdHis') . rand(0, 99) . $pointName;

		return $newName;
	}
}