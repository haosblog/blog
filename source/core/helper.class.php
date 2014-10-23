<?php
/**
 * File: helper.class.class.php
 * Created on : 2014-3-13, 23:47:33
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 助手类，用于加载控制器、模型、组件
 * 一般不直接调用本类，由快捷函数中转
 */

abstract class helper {

	static $obj;

	static function loadController($controller){
		if(is_object(self::$obj['c'][$controller])){
			return self::$obj['c'][$controller];
		}

		if(defined('PLUGINMOD')){
			$controllerPath = HAO_ROOT  .'./source/plugin/'. PLUGINMOD .'/controller/'. $controller .'Action.class.php';
		} else {
			$controllerPath = HAO_ROOT  .'./source/controller/'. $controller .'Action.class.php';
		}
		
		if(!file_exists($controllerPath)){
			$controller = substr(PLUGINMOD, 4);
			$controllerPath = DISCUZ_ROOT  .'./source/plugin/'. PLUGINMOD .'/controller/'. $controller .'Action.class.php';
		}

		if(file_exists($controllerPath)){
			$controllerClass = $controller .'Action';
			require_once $controllerPath;

			if(class_exists($controllerClass)){
				//将METHOD传入构造函数，可用于REST API模式开发
				$httpMethod = strtoupper($_SERVER['REQUEST_METHOD']);
				$controllerobj = new $controllerClass($httpMethod);

				self::$obj['c'][$controller] = $controllerobj;
				return $controllerobj;
			}
		}

		sysmessage('控制器'. $controller .'找不到');
	}

	/**
	 * 加载组件
	 *
	 * @param type $component
	 * @return type
	 */
	static function loadComponent($component){
		if(is_object(self::$obj['c'][$component])){
			return self::$obj['c'][$component];
		}
		list($name, $path, $group, $groupType) = self::_getPath($component, 'com');
		if(file_exists($path)){
			require $path;
			$comClass = $name .'Com';
			$groupType = $groupType == 'system' ? 0 : 1;
			self::$obj['c'][$component] = new $comClass($group, $groupType);
			return self::$obj['c'][$component];
		} else {
			sysmessage('组件'. $component .'找不到');
		}
	}

	static function loadModel($model){
		if(is_object(self::$obj['m'][$model])){
			return self::$obj['m'][$model];
		}

		list($name, $path) = self::_getPath($model, 'model');
		if(file_exists($path)){
			require $path;
			$modelClass = $name .'Model';
			self::$obj['m'][$model] = new $modelClass();
		} else {
			self::$obj['m'][$model] = new model($name);
		}
		return self::$obj['m'][$model];
	}

	/**
	 * 处理由API生成的返回码
	 * @param type $code
	 */
	static function parseApiCode($code){
		if(!is_array($code) && $code != 200){
			$return = array(
				'status' => $code,
				'errmsg' => ''
			);
		} else {
			$return = array(
				'status' => 200,
				'data' => $code
			);
		}
		$json = json_encode($return);
		dexit($json);
	}


	static function _getPath($module, $type){
		if(!in_array($type, array('model', 'store', 'com'))){
			return false;
		}

		list($name, $group, $groupType) = self::_getGroup($module);
		if($groupType == 'system'){
			$path = HAO_ROOT .'./'. $type .'/'. $group .'/'. $name . ucfirst($type) .'.class.php';
		} else {
			$path = DISCUZ_ROOT  .'./source/plugin/'. $group .'/'. $type .'/'. $name . ucfirst($type) .'.class.php';
		}

		return array($name, $path, $group, $groupType);
	}

	static function _getGroup($module){
		if(strpos($module, '/') !== false){
			$groupType = 'system';
			list($group, $name) = explode('/', $module);
		} else {
			$groupType = 'plugin';
			if(strpos($module, ':') !== false){
				list($group, $name) = explode(':', $module);
			} else {
				$group = PLUGINMOD;
				$name = $module;
			}
		}

		return array($name, $group, $groupType);
	}
}