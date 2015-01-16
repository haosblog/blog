<?php
/**
 * File: images.php
 * Created on : 2014-6-17, 0:22:23
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 缩略图
 */


define('IMG_ROOT', dirname(__FILE__) .'/');
define('HAO_ROOT', dirname(IMG_ROOT). '/');

$uri = str_replace('/thumb/', '', filter_input(INPUT_SERVER, 'REQUEST_URI'));
list($size, $path) = explode('/', $uri, 2);

$fileType = substr($path, strrpos($path, '.') + 1);

if(!in_array(strtolower($fileType), array('jpg', 'gif', 'png', 'jpeg')) || strpos($size, 'x') === false){//文件后缀名必须为图片
	errorImg();
}

//缩略图路径规则：
//	/data/thumb/原图路径/原图文件名/尺寸.文件后缀
//	如果原图路径为/data/attachment/forum/201108/21/100501ci8d1e0cx1axskhi.jpg
//	100x100的缩略图路径则为：/data/thumb/attachment/forum/201108/21/100501ci8d1e0cx1axskhi.jpg/100x100.jpg
$thumbFile = IMG_ROOT . $size .'/'. $path;
$thumbPath = dirname($thumbFile);

if(file_exists($thumbFile)){//缩略图存在则直接读取输出
	viewImg($thumbFile, $fileType);
} else {//缩略图不存在则生成
	if(!is_dir($thumbPath)){//缩略图路径不存在，则生成
		createDir($thumbPath);
	}

	list($maxWidth, $maxHeight) = explode('x', $size);
	$sourcePath = HAO_ROOT . $path;
	//生成缩略图
	makethumb($sourcePath, $thumbFile, $maxWidth, $maxHeight) ;

	viewImg($thumbFile, $fileType);
}

/**
 * 输出错误图片
 */
function errorImg(){
	$errFilename = HAO_ROOT .'./static/image/common/nophoto.gif';
	viewImg($errFilename, 'gif');
}

/**
 * 创建目录
 *
 * @param type $path
 */
function createDir($path){
	if (!file_exists($path)){
		createDir(dirname($path));
		mkdir($path, 0777);
	}
}

/**
 * 输出图片
 *
 * @param type $filaname
 * @param type $type
 */
function viewImg($filaname, $type = 'jpg'){
	header('Content-type: image/'. $type);
	$img = file_get_contents($filaname);
	echo($img);
	die();
}

//创建缩略图
//自动缩图$srcFile原文件，大图；$photo_small目标文件，小图；$dstW,$dstH是小图的宽，高。
function makethumb($srcFile, $photo_small, $maxWidth = 0, $maxHeight = 0, $type = 0) {
	//获取原图宽高,默认小图宽高就是原图宽高
	$data = getimagesize($srcFile);
	$srcW = $dstW = $data[0];
	$srcH = $dstH = $data[1];

	//计算小图宽高
	if($maxWidth > 0 && $dstW > $maxWidth){
		$temp = $maxWidth / $dstW;
		$dstW = $maxWidth;
		$dstH = $dstH * $temp;
	}

	if($type == 1){
		if($maxHeight > 0 && $maxHeight > $dstH){
			$dstH = $maxHeight;
			$dstW = $srcW * $maxHeight / $srcH;
		}
	} else {
		if($maxHeight > 0 && $dstH > $maxHeight){
			$temp = $maxHeight / $dstH;
			$dstH = $maxHeight;
			$dstW = $dstW * $temp;
		}
	}

	switch ($data[2]) {
		case 1: //图片类型，1是GIF图
			$im = @ImageCreateFromGIF($srcFile);
		break;
		case 2: //图片类型，2是JPG图
			$im = @imagecreatefromjpeg($srcFile);
		break;
		case 3: //图片类型，3是PNG图
			$im = @ImageCreateFromPNG($srcFile);
		break;
	}
	$ni=imagecreatetruecolor($dstW, $dstH);
	imageCopyreSampled($ni, $im, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
	imagejpeg($ni, $photo_small);
}