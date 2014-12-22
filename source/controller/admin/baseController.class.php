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
	protected $m_user, $where, $wsid;

	function __construct() {
		parent::__construct();	//先执行一遍父类的初始化操作

		$this->m_user = M('user');
		if(isset($_POST['loginmode'])){// 登陆模式，进行登陆验证
			$this->_loginAction();
		}

		$this->islogin();	//检测用户是否登陆
		$this->_getWebSiteInfo();	// 获取站点列表

		$this->buffer['menu'] = $this->_getMenu();
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

	/**
	 *
	 */
	protected function getWsid(){
		return $GLOBALS['wsid'];
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

	/**
	 *
	 */
	private function _getWebSiteInfo(){
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

		$this->wsid = $_SESSION['wsid'];
		$this->where = array('wsid' => $_SESSION['wsid']);
	}
	
	/**
	 * 获取后台菜单列表
	 */
	private function _getMenu(){
		$menu = array(
			// 站点管理
			array(
				'title' => '站点管理',
				'active' => 'site',
				'sub' => array(
					array(
						'title' => '添加站点',
						'active' => 'add',
						'link' => 'site/add'
					),
					array(
						'title' => '站点列表',
						'active' => 'list',
						'link' => 'site'
					),
					array(
						'title' => '域名列表',
						'active' => 'domain',
						'link' => 'site/domain'
					),
				)
			),
			// 日志管理
			array(
				'title' => '日志管理',
				'active' => 'article',
				'sub' => array(
					array(
						'title' => '分类管理',
						'active' => 'category',
						'link' => 'category'
					),
					array(
						'title' => '日志管理',
						'active' => 'list',
						'link' => 'article'
					),
					array(
						'title' => '写日志',
						'active' => 'edit',
						'link' => 'article/edit'
					),
				)
			),
			// 相册管理
			array(
				'title' => '相册管理',
				'active' => 'photo',
				'sub' => array(
					array(
						'title' => '相册管理',
						'active' => 'album',
						'link' => 'album'
					),
					array(
						'title' => '图片管理',
						'active' => 'list',
						'link' => 'photo'
					),
					array(
						'title' => '上传图片',
						'active' => 'upload',
						'link' => 'photo/upload'
					),
				)
			),
			// 系统模型管理
			array(
				'title' => '系统模型管理',
				'active' => 'model',
				'sub' => array(
					array(
						'title' => '模型列表',
						'active' => 'list',
						'link' => 'model'
					),
					array(
						'title' => '导入模型',
						'active' => 'import',
						'link' => 'model/import'
					),
				)
			),
		);
		
		// 运行_getModelMenu方法获取模型的菜单项，并与默认菜单合并返回
		return array_merge($menu, $this->_getModelMenu());
	}
	
	private function _getModelMenu(){
		$modelList = M('model')->field('mid', 'modname', 'tablename', 'classable')->select();
		$modelMenu = array();
		foreach($modelList as $item){
			$tmp = array(
				'title' => $item['modname'] .'管理',
				'active' => $item['tablename'],
				'sub' => array(
					array(
						'title' => '发表'. $item['modname'],
						'active' => $item['add'],
						'link' => 'data/edit?mid='. $item['mid']
					),
					array(
						'title' => $item['modname'] .'列表',
						'active' => 'data',
						'link' => 'data?mid='. $item['mid']
					),
				)
			);
			
			if($item['classable']){
				$tmp['sub'][] = array(
					'title' => $item['modname'] .'分类',
					'active' => 'category',
					'link' => 'category?mid='. $item['mid']
				);
			}
			
			$modelMenu[] = $tmp;
		}
		
		return $modelMenu;
	}
}