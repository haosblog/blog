<?php
/**
 * copyright 小皓 (C)2013-2099 版权所有
 *
 * 基础入口类
 */

$db = null;

abstract class hao_core {
	var $controller, $method;

	public static function init($extend = null){
		define('NL', "\n");
		require HAO_ROOT .'./fun/common.fun.php';
		importCore('helper');
		importCore('controller');
		importCore('store');
		importCore('component');
		importCore('model');
		if(!empty($extend)){
			require $extend;
		}

		$uri = $_SERVER['REQUEST_URI'];
		if(strpos($uri, 'plugin.php') === false){
			$pos = strpos($uri, '?');
			if($pos !== false){
				$queryStr = substr($uri, $pos + 1);
				$uri = substr($uri, 0, $pos);

				$queryArr = array();
				parse_str($queryStr, $queryArr);
				if(is_array($queryArr)){
					$_GET = array_merge($queryArr, $_GET);
				}
			}
			$tmp = explode('/', $uri, 3);
			$uriarr = explode('/', $tmp[2], 3);

			list($controller, $action) = $uriarr;

			define('PATH_MOD', true);
		} else {
			$controller = $_GET['mod'];
			$action = $_GET['ac'];

			define('PATH_MOD', false);
		}

		if(empty($controller)){
			$controller = 'index';
		}
		if(empty($action)){
			$action = 'index';
		}


		$actionObj = HC($controller);
		//执行控制器初始化
		$actionObj->init();

		if(get_class($actionObj) != $controller .'Action'){
			$action = $controller;
		}

		if($action == 'list') {
			$action = 'showlist';
		}

		if(!method_exists($actionObj, $action)){
			$action = 'index';
		}
		$actionObj->$action();

		$GLOBALS['controller'] = $controller;
		$GLOBALS['action'] = $action;
		$GLOBALS['uri'] = $uriarr[2];	//第三个/后面的URL内容将被存至全局变量供控制器调用
	}
}