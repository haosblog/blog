<?php

/**
 * copyright 小皓 (C)2013-2099 版权所有
 *
 * 控制器基类
 */
class controller {
	protected $title, $keyword, $description, $smarty;
	protected $error = false;
	protected $errormsg = '';
	protected $buffer = array();

	/**
	 * 控制器初始化
	 */
	public function __construct($router = array()){ }

	/**
	 * 魔术方法，当运行的方法不存在的时候运行
	 *
	 * @param type $name
	 * @param type $arguments
	 */
	public function __call($name, $arguments) {
		;
	}

	protected function showmessage($message, $status = 0, $jumpurl = ''){
		if(IS_AJAX){
			$this->ajaxShow($status, $message, array('jump' => $jumpurl));
		} else {
			if(empty($jumpurl)){
				$jumpurl = 'javascript:history.back(-1)';
			}

			$this->buffer = array_merge($this->buffer, array(
				'jumpurl' => $jumpurl,
				'message' => $message,
				'status' => $status
			));

			$this->display('tip');
		}
	}


	/**
	 * 交互信息输出
	 *
	 * @param type $status 状态，0表示失败，1表示成功，2以上根据业务需要自定义
	 * @param type $msg 提示信息
	 * @param type $data 自定义数据，默认为空
	 */
	protected function ajaxShow($status, $msg, $data = array()){
		$jsonArr = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		);
		die(json_encode($jsonArr));
	}

	/**
	 * 检测指定的模板是否存在
	 *
	 * @param type $tpl
	 */
	final protected function checkTpl($tpl = ''){
		$tplPath = $GLOBALS['tplPath'] . $this->_getTplPath($tpl);
		return file_exists($tplPath);
	}

	/**
	 * 输出到模板
	 *
	 * @param type $tpl	模板路径如果为空，则调用与控制器同名的模板
	 */
	final protected function display($tpl = ''){
		$tplPath = $this->_getTplPath($tpl);

		// 初始化smarty对象
		$smarty = new Smarty();
		$smarty->template_dir = $GLOBALS['tplPath'];
		$smarty->compile_dir = HAO_ROOT .'data/template/';
		$smarty->config_dir = HAO_ROOT .'data/tplconf/';
		$smarty->cache_dir = HAO_ROOT .'data/cache/';
		$smarty->left_delimiter = '<{';
		$smarty->right_delimiter = '}>';

		$this->buffer['title'] = $this->title;
		$this->buffer['keyword'] = $this->keyword;
		$this->buffer['description'] = $this->description;
		$smarty->assign($this->buffer);

		$smarty->display($tplPath);
		exit();
	}


	protected function checkField($str, $explain,  $rule){
		import('validat', 'fun');
		$ruleArr = $this->parseRule($rule);
		$null = strpos($rule, 'null') === false ? false : true;
		$strlen = strlen($str);

		if(!$null && empty($str)){
			$this->set_err($explain .'不能为空');
		}

		if($null && empty($str)){// 允许为空，且内容为空，则不做判断
			return true;
		}

		foreach($ruleArr as $item){
			if(strpos($item, ':')){
				list($key, $value) = explode(':', $item);

				switch ($key){
					case 'max':// 限制字符长度的最大值
						if($strlen > $value){
							$this->set_err($explain .'最大长度限定为'. $key);
						}
						break;
					case 'min' :// 限制字符长度的最小值
						if($strlen < $value && !(empty($strlen) && $null)){
							$this->set_err($explain .'最小长度限定为'. $key);
						}
						break;

					case 'eq':// 等于某（几）个值
						$eqResult = false;
						if(strpos($value, '|') !== FALSE){
							$valueArr = explode('|', $value);
							foreach($valueArr as $v){
								if($str == $v){
									$eqResult = true;
									break;
								}
							}
						} else {
							$eqResult = $str == $value;
						}

						if(!$eqResult){
							$this->set_err($explain .'值错误'. $key);
						}

						break;

					case 'reg' :// 正则匹配模式
						if(preg_match($value, $str)){
							$this->set_err($explain .'不符合格式');
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

//				case 'url':
//					if(!isUrl($str)){
//						$this->set_err($explain .'必须为URL格式');
//					}
//					break;
				default :

			}
		}
	}

	protected function parseRule($rule){
		if(empty($rule)){
			return array();
		} elseif(strpos($rule, ',') === false) {
			return array($rule);
		} else {
			return explode(',', $rule);
		}
	}

	/**
	 *
	 *
	 * @param type $rule
	 * @param type $method
	 * @param type $ajax
	 * @return type
	 */
	final protected function getParam($rule = array(), $method = 'post', $return = true){
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

		if($this->error && !$return){// 出错，且设置了不返回信息，则调用showmessage
//			if($ajax){
//				$this->ajaxShow(0, $this->errormsg);
//			} else {
				$this->showmessage($this->errormsg);
//			}
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

	protected function getLimit($pageCount = 10){
		$page = $this->getPage();
		$start = ($page - 1) * $pageCount;
		return array($start, $pageCount);
	}

	protected function set_err($msg){
		$this->error = true;
		$this->errormsg = $this->errormsg . $msg ."<br />";
	}

	protected function response($data, $type = 'JSON'){
		$type = strtolower($type);
		switch ($type){
			case 'JSON':
				header('Content-type:text/json');
				$text = json_encode($data);
		}

		echo($text);
	}

	/**
	 * 根据传入的模板路径生成正式的模板路径
	 *
	 * @param type $tpl		模板路径名，若不传入，默认使用当前的控制器与方法自动拼装
	 * @return string
	 */
	private function _getTplPath($tpl = ''){
		if(empty($tpl) || is_array($tpl)){
			$tpl = $GLOBALS['controller'] . '/'. $GLOBALS['action'];
		}

		if(strpos($tpl, '.') === false){// 模板路径中不存在点，则补全默认的后缀名tpl
			$tpl .= '.tpl';
		}

		return $tpl;
	}
}
?>