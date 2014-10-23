<?php

/**
 * copyright 小皓 (C)2013-2099 版权所有
 *
 * 控制器基类
 */
abstract class controller {
	protected $title, $keyword, $description, $smarty;
	protected $error = false;
	protected $errormsg = '';
	protected $buffer = array();

	/**
	 * 控制器初始化
	 */
	public function __construct($router = array()){
		//创建smarty对象
		$tpl = new Smarty();
		$tpl->template_dir = HAO_ROOT . TPL_PATH;
		$tpl->compile_dir = HAO_ROOT .'data/template/';
		$tpl->config_dir = HAO_ROOT .'data/tplconf/';
		$tpl->cache_dir = HAO_ROOT .'data/cache/';
		$tpl->left_delimiter = '<{';
		$tpl->right_delimiter = '}>';

		$this->smarty = $tpl;
	}

	protected function showmessage($message, $status = 0, $jumpurl = ''){
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
	 * 输出到模板
	 *
	 * @param type $tpl	模板路径如果为空，则调用与控制器同名的模板
	 */
	protected function display($tpl = ''){

		if(empty($tpl) || is_array($tpl)){
			$tpl = $GLOBALS['controller'] . '/'. $GLOBALS['action'];
		}

		$this->buffer['title'] = $this->title;
		$this->buffer['keyword'] = $this->keyword;
		$this->buffer['description'] = $this->description;
		$this->smarty->assign($this->buffer);

		$tpl .= '.tpl';
		$this->smarty->display($tpl);
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

	protected function response($msg, $typr = 0, $json = false){

	}
}
?>