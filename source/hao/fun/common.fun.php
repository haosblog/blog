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
 * ��ȡ���ݲֿ�
 *
 * @param string $store
 */
function D($store){
	return helper::loadStore($store);
}

/**
 * ʵ����������󲢷���
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
 * ����Դ��
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
 * ǿ��ת�������е�����Ԫ��Ϊ����
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
 * ��ά����⹹Ϊһά����
 */
function array_lower($array, $key = ''){
	$return = array();
	foreach($array as $item){
		$return[] = $item[$key];
	}

	return $return;
}

/**
 * ��ά����ָ��������
 *
 * @param array $arr		��Ҫ����Ķ�ά����
 * @param string $keys	ָ����
 * @param string $type	�������
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
 * ����Discuz�ڲ�����
 *
 * @param type $function	�����жϺ����Ƿ����
 * @param type $lib
 */
function importDiscuz($lib, $function){
	if(empty($function) || !function_exists($function)){
		require_once libfile('function/'. $lib);
	}
}

/**
 * ���ݷ�ҳ��������limit
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