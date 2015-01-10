<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2015-1-6 18:42:44
 *
 * 请在此处输入文件注释
 */


class commentController extends baseController {

	public function index(){
		$page = $this->getPage();
		$where = array(
			'wsid' => $this->wsid,
			'type' => 0
		);

		$this->buffer['list'] = M('comment')->where($where)->select();

		$this->display();
	}
	
	public function action(){
		$t = I('get.t');	// 是否来源于ajax请求
		
		$rule = array(
			'username' => array('explain' => '用户名', 'rule' => ''),
			'title' => array('explain' => '留言标题', 'rule' => 'null,max:30'),
			'portrait' => array('explain' => '头像'),
			'sex' => array('explain' => '性别', 'rule' => 'eq:m|f'),
			'content' => array('explain' => '留言内容'),
			'email' => array('explain' => '邮箱地址', 'rule' => 'null,email'),
		);
		
		$maps = $this->getParam($rule);
		
		if($this->error){
			if($t){
				$this->ajaxShow(0, $this->errormsg);
			} else {
				$this->showmessage($this->errormsg);
			}
		}
		
		$maps['type'] = $type = I('type', 0, 'intval');
		
		if($type == 1 || $type == 2 || $type == 3){// 评论文章、图片、模型，则需要添加fid
			$maps['fid'] = I('fid', 0, 'intval');
			
			if(!$maps['fid']){
				$this->showmessage('评论主题错误');
			}
	
			if ($type == 3){// 评论模型，需要确定模型id
				$maps['mid'] = I('mid', 0, 'intval');

				if(!$maps['mid']){
					$this->showmessage('评论主题错误');
				}
			}
		}

		$maps['reply'] = I('reply');
		$maps['ip'] = get_ip();
		$maps['time'] = time();
		$maps['wsid'] = $this->wsid;
		
		M('comment')->insert($maps);
		
		$this->showmessage('留言发布成功！', 1);
	}
}