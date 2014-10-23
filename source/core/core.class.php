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

//		self::websiteInfo($tplPath);
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

		$GLOBALS['tpl_path'] =  HAO_ROOT;	//模板路径，默认先定位到根目录，如果启用了分组或插件模式，则转到相应的模板目录
		
		
		if($urlInfo['path'] === '/' || $urlInfo['path'] === '/index.php'){
			$controller = $action = 'index';
		} else {
			list($controller, $action) = self::_getAction($urlInfo['path']);
		}
		
		
		$GLOBALS['controller'] = $controller;
		$GLOBALS['action'] = $action;

		if(empty($action)){
			$action = 'index';
		}

		$controllerObj = A($controller);
		if(!$controllerObj){
			$controllerObj = A('default');
		}

		// 运行方法
		call_user_func(array($controllerObj, $action));
	}

	/**
	 * 读取控制器与方法的名
	 * 规则为：[分组]/控制器/[方法]
	 * 首先判断是否有对应分组配置，存在则调用分组
	 * 获取到的分组名、控制器名、方法名将存于$GLOBAL['controller']中
	 * 
	 * @param type $path
	 */
	private static function _getAction($path){
		$router = explode('/', $path);
		$controller = $router[1];
		$sitegroupArr = C('GROUP');
		$tplPath = HAO_ROOT .'./template/';
		
		if($controller == 'extend'){
			
		} elseif(isset($sitegroupArr[$controller])){
			$GLOBALS['sitegroup'] = $sitegroup = $router[1];
			$controller = !empty($router[2]) ? $router[2]: 'index';
			$action = !empty($router[3]) ? $router[3]: '';

			$tplPath .= $sitegroup .'/';
			
			$extendBase = HAO_ROOT .'source/controller/'. $sitegroup .'/baseController.class.php';
			if(file_exists($extendBase)){// 如果存在扩展的控制器基类
				require $extendBase;
			}
			
		} else {// 不存在分组
			$tplPath = 'default';
			$action = !empty($router[2]) ? $router[2]: 'index';
		}

		$GLOBALS['tplPath'] = $tplPath;

		return array($controller, $action);
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