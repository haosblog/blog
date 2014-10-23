<?php

/**
 * copyright С� (C)2013-2099 ��Ȩ����
 *
 * ����������
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
	 * ��������ʼ��
	 */
	public function init(){

	}


	/**
	 * �����ģ��
	 *
	 * @param type $tpl	ģ��·�����Ϊ�գ�������������ͬ����ģ��
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
			system_error('������ʾ����', true, false);
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
			$this->set_err($explain .'����Ϊ��');
		}

		foreach($ruleArr as $item){
			if(strpos($item, ':')){
				list($key, $value) = explode(':', $item);

				switch ($key){
					case 'max':
						if($strlen > $value){
							$this->set_err($explain .'��󳤶��޶�Ϊ'. $key);
						}
						break;
					case 'min' :
						if($strlen < $value && !(empty($strlen) && $null)){
							$this->set_err($explain .'��С�����޶�Ϊ'. $key);
						}
						break;
					case 'reg' :
						if(preg_match($value, $str)){
							$this->set_err($explain .'�����ϸ�ʽ����');
						}
				}
			}
			switch ($item){
				case 'null' :
					$null = true;
					break;
				case 'number' :
					if(!is_numeric($str)){
						$this->set_err($explain .'����Ϊ����');
					}
					break;
				case 'email' :
					if(!isEmail($str)){
						$this->set_err($explain .'���Ǻϸ��E-mail��ʽ');
					}
					break;
				case 'mobile' :
					if(!isMobile($str)){
						$this->set_err($explain .'���Ǻϸ���ֻ������ʽ');
					}
					break;
				case 'cn' :
					if(!isCn($str)){
						$this->set_err($explain .'����Ϊ����');
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
	 * ��ȡ��ǰ��ҳ
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