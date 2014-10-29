<?php
/**
 * File: adminBasic.class.php
 * Created on : 2013-12-8, 19:48:30
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 后台管理基类，所有后台控制器均继承本类
 */

session_start();

abstract class baseController extends controller {
	protected $m_user;

	function __construct() {
		parent::__construct();	//先执行一遍父类的初始化操作
		
		$this->m_user = M('user');
		if(isset($_POST['loginmode'])){// 登陆模式，进行登陆验证
			$this->_loginAction();
		}

		$this->islogin();	//检测用户是否登陆

		//加载站点列表
		$websiteList = M('website')->field('wsid', 'sitename', 'isdefault')->where(array('type' => 0))->select();
		if($websiteList){
			//如果SESSION中没有当前站点wsid信息，则初始化站点
			if(!$_SESSION['wsid']){
				$_SESSION['wsid'] = $_SESSION['user']['wsid'];
			}
			$GLOBALS['website']['list'] = $websiteList;
			$GLOBALS['wsid'] = $_SESSION['wsid'];
		}

		$field = array('mid', 'modname', 'tablename', 'classable');
		$this->buffer['model'] = M('model')->select($field);
	}


	/**
	 * 判断是否已登录后台
	 * 如果未登录，则直接输出登陆模板
	 */
	protected function islogin(){
		if(!isset($_SESSION['user']['uid'])){
			$cookie = getCookie('admin');
			if(isset($cookie)){
				$userdata = $this->m_user->cookieLogin($cookie);
				if($userdata){
					$_SESSION['user'] = $userdata;

					return true;
				}
			}

			$this->display('login/index');
		}
	}


	/**
	  * 用户控制
	  * @param type $leave 用户等级
	  *			0 / root	根用户，最高权限
	  *			1 / site	网站管理员，只允许管理本站
	  *			2 / eidtor	编辑，只有发文章权限
	  */
	protected function userControl($leave){
		if(is_numeric($leave)){
			$allowleave = $leave;
		} else {
			$change = array(
				'root' => 0,
				'site' => 1,
				'editor' => 2
			);
			if(isset($change[$leave])){
				$allowleave = $change[$leave];
			} else {
				showerror('用户等级输入错误');
			}

			if($this->checkLogin())
			$_HAO['user']['model'] = $usermodel;
		}
	}

	protected function cookieLogin($cookie){

	}
	
	/**
	 * 当用户提交了登陆表单，则执行本方法进行验证
	 */
	private function _loginAction(){
		$rule = array(
			'username' => array('explain' => '用户名', 'rule' => ''),
			'password' => array('explain' => '密码', 'rule' => '')
		);

		$param = $this->getParam($rule);

		if($this->error){
			$this->showmessage($this->errormsg);
		}

		$param['password'] = md5($param['password']);
		$userdata = $this->m_user->where($param)->selectOne();
		if($userdata){
			$_SESSION['user'] = $userdata;
		} else {
			$this->showmessage('账号密码错误！');
		}
	}
}