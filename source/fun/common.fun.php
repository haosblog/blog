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
	} else {
		return new model($model);
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

function C($name = NULL, $value = NULL){
	static $config = array();

	if(empty($config)){
		$config = include HAO_ROOT .'./config/config.php';
	}

	if(!$name){// 未传入名，则返回整个配置文件
		return $config;
	}

	$name = strtoupper($name);	// 将配置字段转为大写，大小写不敏感

	if(!empty($value)){// 传入了value，则给对应配置赋值
		$config[$name] = $value;

		return $value;
	}

	// 返回对应的配置值
	return isset($config[$name]) ? $config[$name] : null;
}

/**
 *
 * @param type $controller
 * @return \actionclass|boolean
 */
function A($controller){
	if(controller_exists($controller)){// 控制器文件存在
		$controllerClass = $controller .'Controller';
		if(!class_exists($controllerClass)){
			require controller_file($controller);
		}
		if(class_exists($controllerClass)){
			$controllerobj = new $controllerClass();
			return $controllerobj;
		}
	} else {
		return FALSE;
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
	$path = isset($GLOBALS['sitegroup']) ? $GLOBALS['sitegroup'] .'/' : '';
	return HAO_ROOT  .'./source/controller/'. $path . $controller .'Controller.class.php';
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

			$jsPath = TPL_PATH .'static/'. ($GLOBALS['sitegroup'] ? $GLOBALS['sitegroup'] .'/' : '') .'js/';
			$jsfile .= '.js';

			$filePath = HAO_ROOT . $jsPath .$jsfile;
			if(!file_exists($filePath)){
				$jsPath = '/static/'. ($GLOBALS['sitegroup'] ? $GLOBALS['sitegroup'] .'/' : '') .'js/';
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

			$cssPath = TPL_PATH .'static/'. ($GLOBALS['sitegroup'] ? $GLOBALS['sitegroup'] .'/' : '') .'css/';
			$cssfile .= '.css';

			$filePath = HAO_ROOT . $cssPath .$cssfile;
			if(!file_exists($filePath)){
				$cssPath = '/static/'. ($GLOBALS['sitegroup'] ? $GLOBALS['sitegroup'] .'/' : '') .'css/';
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

/**
 * 获取文件缓存的数据
 * 缓存规则：
 * 将filename使用16位MD5加密，生成一个唯一字符串，并将该字符串作为函数名
 * 需要缓存的数据将被var_export为PHP代码并由该函数return
 * getCache时调用该函数得到数据
 *
 * @param type $filename	缓存文件名，不包括完整路径
 */
function getCache($filename){
	$filename = RUNTIME_PATH . $filename;
	$function = 'cache_'. substr(md5($filename),8,16);
	if(!function_exists($function)){//函数不存在，则加载文件
		if(!file_exists($filename)){//文件不存在
			return false;
		}

		require_once $filename;

		if(!function_exists($function)){
			return false;
		}
	}

	return $function();
}

/**
 * 将数据写入文件缓存
 * 缓存规则：
 * 将filename使用16位MD5加密，生成一个唯一字符串，并将该字符串作为函数名
 * 需要缓存的数据将被var_export为PHP代码并由该函数return
 *
 * @param type $filename	缓存文件名，不包括完整路径
 * @param type $data		要缓存的数据
 */
function saveCache($filename, $data = array()){
	$filename = RUNTIME_PATH . $filename;
	$function = 'cache_'. substr(md5($filename),8,16);

	$code = var_export($data, true);
	//生成缓存文件的PHP代码
	$fileText = '<?php'. NL .
		'function '. $function .'(){'. NL .
		'return '. $code .';' . NL .
		'}';

	file_put_contents($filename, $fileText);
}