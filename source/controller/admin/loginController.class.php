<?php

/**
 * Description of login
 *
 * @author hao
 */
class loginController extends controller {
	public function index(){
		$this->display();
	}

	public function action(){
		$rule = array(
			'username' => array('explain' => '用户名', 'rule' => ''),
			'password' => array('explain' => '密码', 'rule' => '')
		);

		$param = $this->getParam($rule);

		if($this->error){
			$this->showmessage($this->errormsg);
		}

		$param['password'] = md5($param['password']);
		$m_user = M('user');
		$userdata = $m_user->where($param)->selectOne();
		if($userdata){
			$_SESSION['user'] = $userdata;
			$this->showmessage('登陆成功！');
		} else {
			$this->showmessage('账号密码错误！');
		}
	}
}
