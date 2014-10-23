<?php

/**
 *
 * @param type $model
 * @return \moduleclass|boolean
 */
function M($model){
	return helper::loadModel($model);
}

/**
 * 读取数据仓库
 *
 * @param string $store
 */
function D($store){
	return helper::loadStore($store);
}

/**
 * 实例化组件对象并返回
 *
 * @date 2014- 7-6 12:32
 * @param type $component
 * @return type
 */
function COM($component){
	return helper::loadComponent($component);
}

/**
 *
 * @param type $controller
 * @return \actionclass|boolean
 */
function HC($controller){
	return helper::loadController($controller);
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

	$libpath = HAO_ROOT .'./'. $type .'/';
	$libpath .= $lib .'.'. $type .'.php';
	if(file_exists($libpath)){
		require_once $libpath;
	}

	return false;
}

function importCore($core){
	$path = HAO_ROOT .'./core/'. $core .'.class.php';

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
	system_error($msg);
}

 function utf82gbk($data){
	if(is_array($data)){
		return array_map('utf82gbk', $data);
	}
	return diconv($data, 'utf-8');
}

function hjson_encode($json){
	if(is_array($json)){
		$gbkArr = gbk2utf8($json);
		$jsonText = json_encode($gbkArr);
		return $jsonText;
	} else {
		return false;
	}
}

 function gbk2utf8($data){
	if(is_array($data)){
		return array_map('gbk2utf8', $data);
	}
	return diconv($data, 'GBK', 'UTF-8');
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

/**
 * 二维数组指定键排序
 *
 * @param array $arr		需要排序的二维数组
 * @param string $keys	指定键
 * @param string $type	升序或降序
 * @return array
 */
function array_sort($arr,$keys,$type='asc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}

/**
 * 引入Discuz内部函数
 *
 * @param type $function	用于判断函数是否存在
 * @param type $lib
 */
function importDiscuz($lib, $function){
	if(empty($function) || !function_exists($function)){
		require_once libfile('function/'. $lib);
	}
}

/**
 * 根据分页数据生成limit
 *
 * @param type $page
 * @param type $pageCount
 * @return string
 */
function getLimit($page, $pageCount){
	if($pageCount == 0){
		return '';
	}

	$start = ($page - 1) * $pageCount;
	return array($start, $pageCount);
}