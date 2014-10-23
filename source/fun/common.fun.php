<?php

/**
 * 获取系统模型对象
 *
 * @param type $mid
 * @return \systemModel
 */
function SM($mid){
	importCore('systemModel');

	$modelObj = new systemModel($mid);
	return $modelObj;
}

/**
 *
 * @param type $model
 * @return \moduleclass|boolean
 */
function M($model){
	if(model_exists($model)){
		$modelClass = $model .'Model';
		if(!class_exists($modelClass)){
			require_once model_file($model);
		}
		if(class_exists($modelClass)){
			$modelObj = new $modelClass();
			return $modelObj;
		}
	}

	showerror('无法找到模型'. $model);
}

function model_exists($model){
	$modelFile = model_file($model);
	if(file_exists($modelFile)){
		return true;
	} else {
		return false;
	}
}

function model_file($model){
	return HAO_ROOT .'./source/model/'. $model .'Model.class.php';
}

/**
 *
 * @param type $controller
 * @return \actionclass|boolean
 */
function C($controller){
	if(controller_exists($controller)){
		$controllerClass = $controller .'Action';
		if(!class_exists($controllerClass)){
			require controller_file($controller);
		}
		if(class_exists($controllerClass)){
			$controllerobj = new $controllerClass();
			return $controllerobj;
		}
	}
	showerror('无法找到控制器'. $controller);
}

function controller_exists($controller){
	$controllerFile = controller_file($controller);
	if(file_exists($controllerFile)){
		return true;
	} else {
		return false;
	}
}

function controller_file($controller){
	$path = defined('ADMIN') ? 'admin/' : '';
	return HAO_ROOT  .'./source/controller/'. $path . $controller .'Action.class.php';
}

/**
 * 引入源码
 * @param type $lib
 * @param string $type
 */
function import($lib, $type = 'class'){
	$typelist = array('class', 'fun');
	if(!in_array($type, $typelist)){
		$type = 'class';
	}

	$libpath = HAO_ROOT .'./source/'. $type .'/';
	$libpath .= $lib .'.'. $type .'.php';
	if(file_exists($libpath)){
		require_once $libpath;
	}

	return false;
}

function importCore($core){
	$path = HAO_ROOT .'./source/core/'. $core .'.class.php';

	require_once $path;
}


function loadJS($jsfiles){
	if(!empty($jsfiles)){
		$jsArr = explode(',', $jsfiles);

		foreach ($jsArr as $jsfile){
			$jsfile = trim($jsfile);
			if(empty($jsfile)){
				continue;
			}

			$jsPath = TPL_PATH .'static/js/';
			$jsfile .= '.js';

			$filePath = HAO_ROOT . $jsPath .$jsfile;
			if(!file_exists($filePath)){
				$jsPath = '/static/js/';
			}

			echo('<script type="text/javascript" src="'. $jsPath . $jsfile .'"></script>'. NL);
		}
	}
}


function loadCSS($cssfiles){
	if(!empty($cssfiles)){
		$cssArr = explode(',', $cssfiles);

		foreach ($cssArr as $cssfile){
			$cssfile = trim($cssfile);
			if(empty($cssfile)){
				continue;
			}

			$cssPath = TPL_PATH .'static/css/';
			$cssfile .= '.css';

			$filePath = HAO_ROOT . $cssPath .$cssfile;
			if(!file_exists($filePath)){
				$cssPath = '/static/css/';
			}

			echo('<link rel="stylesheet" href="'. $cssPath . $cssfile .'" />'. NL);
		}
	}
}



/**
 * 设置cookie
 * @param string $name
 * @param type $value
 * @param type $expire
 */
function hSetCookie($name, $value, $expire){
	$name = 'HAO_'. $name;
	setcookie($name, $value, $expire);
}


/**
 * 获取cookie值
 *
 * @param string $name
 * @return type
 */
function getCookie($name){
	$name = 'HAO_'. $name;
	return isset($_COOKIE[$name]) ? htmlspecialchars($_COOKIE[$name], 3) : '';
}


/**
 * 强制转换数组中的所有元素为整型
 *
 * @param type $arr
 * @return type
 */
 function intvalArray($arr){
	if(is_array($arr)){
		return array_map('intvalArray', $arr);
	}
	return intval($arr);
}

 function addslashesArray($arr){
	if(is_array($arr)){
		return array_map('addslashesArray', $arr);
	}
	return addslashes($arr);
}

function showerror($msg){
	import('error');
	error::trhow($msg);
}


/**
 * 二维数组解构为一维数组
 */
function array_lower($array, $key = ''){
	$return = array();
	foreach($array as $item){
		$return[] = $item[$key];
	}

	return $return;
}