<?php
/**
 * copyright 小皓 (C)2013-2099 版权所有
 *
 * 基础入口类
 */

$db = null;

abstract class core {
	var $controller, $method;

	public static function init(){
		define('NL', "\n");
		define('HAO_ROOT', substr(dirname(__FILE__), 0, -11));
		require HAO_ROOT .'./source/fun/common.fun.php';
		require HAO_ROOT .'./config/config.php';
		require HAO_ROOT .'./source/core/smarty/Smarty.class.php';
		require HAO_ROOT .'/source/interface/database.interface.php';
		require HAO_ROOT .'/source/driver/mysql.class.php';
		
		importCore('DB');
		importCore('controller');
		importCore('model');
		
		//初始化数据操作类
		DB::init();




		self::websiteInfo($tplPath);

		define('TPL_PATH', $tplPath);

		if(empty($action)){
			$action = 'index';
		}

		if($action == 'list') {
			$action = 'showlist';
		}

		$GLOBALS['controller'] = $controller;
		$GLOBALS['action'] = $action;
		if(!defined('ADMIN') && $controller != 'index'){
			if($controller != 'plugin'){
				$controller = 'model';
			}
			$controllerObj = C($controller, $router);
//			$controllerObj->init($router);
		} else {
			$actionObj = C($controller);
			//执行控制器初始化
//			$actionObj->init();
		}
		
		if(!method_exists($actionObj, $action)){//控制器中没有指定的方法，尝试输出指定模板
			$actionObj->display();
		} else {
			call_user_func(array($actionObj, $action));
		}
	}
	
	public static function run(){
		self::init();

		// $cachemodel = M('cache');
		// 获取控制器名和方法名
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$urlInfo = parse_url($url);

		//保存站点URL信息到全局变量
		$GLOBALS['host'] = $urlInfo['host'];
		$GLOBALS['port'] = isset($urlInfo['port']) ? $urlInfo['port'] : 80;
		$GLOBALS['path'] = $urlInfo['path'];

		$tplPath =  HAO_ROOT;	//模板路径，默认先定位到根目录，如果启用了分组或插件模式，则转到相应的模板目录
		
		
		if($urlInfo['path'] === '/' || $urlInfo['path'] === '/index.php'){
			$controller = $action = 'index';
		} else {
			$router = explode('/', $urlInfo['path']);
			$controller = $router[1];

			// Admin mode:  /admin, /admin/index/,  /admin/user/ ...
			if($controller == 'admin'){
				$controller = !empty($router[2]) ? $router[2]: 'index';
				$action = isset($router[3]) ? $router[3]: '';
				$tplPath .= 'admin/';

				require HAO_ROOT .'source/controller/admin/adminBase.class.php';
				define('ADMIN', 1);
				define('ADMIN_PATH', HAO_ROOT.'admin');
			} else {
				$action = $router[2];
			}
		}
	}


	/*
	 * 加载站点数据
	 */
	private static function websiteInfo(&$tplPath){
		//初始化当前站点数据，以确定应载入模板路径以及首页SEO内容，后台无需执行此步骤
		if(!defined('ADMIN')){
			$m_website = M('website');
			//print_r($m_website);
			$website = $m_website->loadDefault();
			$GLOBALS['website'] = $website;
			$tplPath .= $website['tppath'] .'/';
		}
	}
}
?>