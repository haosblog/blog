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
	$path = isset($GLOBALS['sitegroup']) ? $GLOBALS['sitegroup'] .'/' : 'home/';
	return HAO_ROOT  .'./source/controller/'. $path . $controller .'Controller.class.php';
}


/**
 * 获取输入参数 支持过滤和默认值
 * 使用方法:
 * <code>
 * I('id',0); 获取id参数 自动判断get或者post
 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
 * I('get.'); 获取$_GET
 * </code>
 * @param string $name 变量的名称 支持指定类型
 * @param mixed $default 不存在的时候默认值
 * @param mixed $filter 参数过滤方法
 * @param mixed $datas 要获取的额外数据源
 * @return mixed
 */
function I($name,$default='',$filter=null,$datas=null) {
    if(strpos($name,'.')) { // 指定参数来源
        list($method,$name) =   explode('.',$name,2);
    }else{ // 默认为自动判断
        $method =   'param';
    }
    switch(strtolower($method)) {
        case 'get'     :   $input =& $_GET;break;
        case 'post'    :   $input =& $_POST;break;
        case 'put'     :   parse_str(file_get_contents('php://input'), $input);break;
        case 'param'   :
            switch($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $input  =  $_POST;
                    break;
                case 'PUT':
                    parse_str(file_get_contents('php://input'), $input);
                    break;
                default:
                    $input  =  $_GET;
            }
            break;
        case 'path'    :   
            $input  =   array();
            if(!empty($_SERVER['PATH_INFO'])){
                $input  =   explode('/', trim($_SERVER['PATH_INFO'], '/'));            
            }
            break;
        case 'request' :   $input =& $_REQUEST;   break;
        case 'session' :   $input =& $_SESSION;   break;
        case 'cookie'  :   $input =& $_COOKIE;    break;
        case 'server'  :   $input =& $_SERVER;    break;
        case 'globals' :   $input =& $GLOBALS;    break;
        case 'data'    :   $input =& $datas;      break;
        default:
            return NULL;
    }
    if(''==$name) { // 获取全部变量
        $data       =   $input;
        array_walk_recursive($data,'filter_exp');
        $filters    =   isset($filter)?$filter:'htmlspecialchars';
        if($filters) {
            if(is_string($filters)){
                $filters    =   explode(',',$filters);
            }
            foreach($filters as $filter){
                $data   =   array_map_recursive($filter,$data); // 参数过滤
            }
        }
    }elseif(isset($input[$name])) { // 取值操作
        $data       =   $input[$name];
        is_array($data) && array_walk_recursive($data,'filter_exp');
        $filters    =   isset($filter)?$filter: 'htmlspecialchars';
        if($filters) {
            if(is_string($filters)){
                $filters    =   explode(',',$filters);
            }elseif(is_int($filters)){
                $filters    =   array($filters);
            }
            
            foreach($filters as $filter){
                if(function_exists($filter)) {
                    $data   =   is_array($data)?array_map_recursive($filter,$data):$filter($data); // 参数过滤
                }else{
                    $data   =   filter_var($data,is_int($filter)?$filter:filter_id($filter));
                    if(false === $data) {
                        return   isset($default)?$default:NULL;
                    }
                }
            }
        }
    }else{ // 变量默认值
        $data       =    isset($default)?$default:NULL;
    }
    return $data;
}

function array_map_recursive($filter, $data) {
     $result = array();
     foreach ($data as $key => $val) {
         $result[$key] = is_array($val)
             ? array_map_recursive($filter, $val)
             : call_user_func($filter, $val);
     }
     return $result;
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
 * URL重定向
 * 
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time=0, $msg='') {
    if (empty($msg)){
		$msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
	}
    if (!headers_sent()) {  
        // redirect
        if (0 === $time) {
			// 使用301跳转，TODO，未来可能更新
			header('HTTP/1.1 301 Moved Permanently'); 
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
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