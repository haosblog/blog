<?php

/**
 * copyright 小皓 (C)2013-2099 版权所有
 *
 * 控制器基类
 */
abstract class controller {
	protected $title, $keyword, $description, $style, $script;
	protected $error = false;
	protected $errormsg = '';
	protected $buffer = array();
	protected $method = '';

	public function __construct($method = '') {
		global $_G;

		if($_GET['inajax'] && !$_G['inajax']){
			$_G['inajax'] = 1;
		}
		$this->method = $method;
	}

	/**
	 * 控制器初始化
	 */
	public function init(){

	}


	/**
	 * 输出到模板
	 *
	 * @param type $tpl	模板路径如果为空，则调用与控制器同名的模板
	 */
	protected function display($tpl, $buffer = array()){
		if(empty($tpl) || is_array($tpl)){
			$tpl = $GLOBALS['controller'] . '_'. $GLOBALS['action'];
		}

		global $_G;

		$buffer = array_merge($buffer, $this->buffer);
		$buffer['title'] = $this->title;
		$buffer['keyword'] = $this->keyword;
		$buffer['description'] = $this->description;
		$buffer['modStyle'] = $this->style;
		$buffer['modScript'] = $this->script;
		extract($buffer);

		include template($tpl);
		exit();
	}

	protected function showJson($json) {
//		header('Content-type:text/json');
		$str = hjson_encode($json);
		if($str === false){
			system_error('数据显示出错！', true, false);
		} else {
			echo($str);
			exit();
		}
	}


	protected function checkField($str, $explain,  $rule){
		import('validat', 'fun');
		$ruleArr = $this->parseRule($rule);
		$null = strpos($rule, 'null') === false ? false : true;
		$strlen = strlen($str);

		if(!$null && empty($str)){
			$this->set_err($explain .'不能为空');
		}

		foreach($ruleArr as $item){
			if(strpos($item, ':')){
				list($key, $value) = explode(':', $item);

				switch ($key){
					case 'max':
						if($strlen > $value){
							$this->set_err($explain .'最大长度限定为'. $key);
						}
						break;
					case 'min' :
						if($strlen < $value && !(empty($strlen) && $null)){
							$this->set_err($explain .'最小长度限定为'. $key);
						}
						break;
					case 'reg' :
						if(preg_match($value, $str)){
							$this->set_err($explain .'不符合格式奥球');
						}
				}
			}
			switch ($item){
				case 'null' :
					$null = true;
					break;
				case 'number' :
					if(!is_numeric($str)){
						$this->set_err($explain .'必须为数字');
					}
					break;
				case 'email' :
					if(!isEmail($str)){
						$this->set_err($explain .'不是合格的E-mail格式');
					}
					break;
				case 'mobile' :
					if(!isMobile($str)){
						$this->set_err($explain .'不是合格的手机号码格式');
					}
					break;
				case 'cn' :
					if(!isCn($str)){
						$this->set_err($explain .'必须为中文');
					}
					break;
				default :

			}
		}
	}

	protected function parseRule($rule){
		if(empty($rule)){
			return array();
		} elseif(strpos($rule, '|') === false) {
			return array($rule);
		} else {
			return explode('|', $rule);
		}
	}

	protected function getParam($rule = array(), $method = 'post', $ajax = false){
		$param = array();

		if($method == 'post'){
			$data = $_POST;
		} else {
			$data = $_GET;
		}
		foreach ($rule  as $key => $value){
			if(is_array($value)){
				$param[$key] = isset($data[$key]) ? $data[$key] : '';
				$this->checkField($data[$key], $value['explain'], $value['rule']);
				if(!$value['html']){
					$param[$key] = htmlspecialchars($param[$key]);
				} else {
					$param[$key] = addslashes($param[$key]);
				}
			} else {
				$param[$key] = htmlspecialchars($value);
			}
		}

		if($this->error){
			if($ajax){
				$this->ajaxShow(0, $this->errormsg);
			} else {
				$this->showmessage($this->errormsg);
			}
		}

		return $param;
	}


	/**
	 * 获取当前分页
	 * @return type
	 */
	protected function getPage(){
		return max(1, intval($_GET['page']));
	}

	protected function set_err($msg){
		$this->error = true;
		$this->errormsg = $this->errormsg . $msg ."<br />";
	}

	protected function _getCache($cachelist = array()){
		if(!is_array($cachelist)){
			loadcache($cachelist);
		} else {
			foreach($cachelist as $item){
				loadcache($item);
			}
		}
	}
}