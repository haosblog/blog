<?php
/**
 * File: $(name)
 * Created on : 2013-12-15, 23:36:47
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 后台站点管理控制器
 */
class siteController extends baseController{
	var $m_website;

	public function __construct() {
		$this->m_website = M('website');

		parent::__construct();
	}

	/**
	 * 站点管理首页
	 */
	public function index(){
		$page = $this->getPage();

		$this->buffer['list'] = $this->m_website->field('wsid', 'sitename', 'isdefault', 'type', 'tpid')->limit((($page - 1) * 20), 20)->select();
		$this->buffer['page'] = $page;

		$this->display();
	}


	/**
	 * 增加站点
	 */
	public function add(){
		$this->buffer['tplist'] = M('template')->loadList();
		$this->display();
	}


	/**
	 * 编辑站点
	 */
	public function edit(){
		$tplList = M('template')->loadList();
		$wsid = intval($_GET['wsid']);

	}


	/**
	 * 添加/编辑站点执行
	 */
	public function addAction(){
		$rule = array(
			'wsid' => array('explain' => '站点ID', 'rule' => 'null'),
			'sitename' => array('explain' => '站点名', 'rule' => 'max:30'),
			'seotitle' => array('explain' => '站点标题', 'rule' => 'max:255'),
			'keyword' => array('explain' => '站点关键字', 'rule' => 'null'),
			'description' => array('explain' => '站点描述', 'rule' => 'null'),
			'tpid' => array('explain' => '站点模板', 'rule' => ''),
			'isdefault' => array('explain' => '默认站点', 'rule' => 'null')
		);

		$param = $this->getParam($rule);

		$tppath = M('template')->getPath($param['tpid']);

		if(!$tppath){
			showmessage('模板选择错误！');
		}

		$param['tppath'] = $tppath;
		$domain = $_POST['domain'];

		$wsid = $this->m_website->updateData($param);
		M('domain')->addList($wsid, $domain);

		$this->showmessage('站点添加成功！', 1, '/admin/site/');
	}


	public function delete(){
		$wsid = intval($_GET['wsid']);
		if($wsid < 1){
			$this->showmessage('ID选择错误！');
		} else {
			$this->m_website->delete($wsid, 'wsid');
			$this->showmessage('站点删除成功！', 1);
		}
	}


	/**
	 * 查看站点列表
	 */
	public function domain(){
		$page = $this->getPage();
		$wsid = intval($_GET['wsid']);
		
		$field = array(
			'd' => array('did', 'domain'),
			's' => array('wsid', 'sitename')
		);
		
		$where = array();
		if($wsid > 0){
			$where = array('d.wsid' => $wsid);
		}
		$this->buffer['list'] = M('domain')->field($field)->where($where)
				->join('website AS s', 's.wsid=d.wsid')->alias('d')->select();

		print_r(M('domain')->getLastSQL());
		$this->display();
	}
}