<?php

/**
 * File: indexAction.class.php
 * Created on : 2013-12-27, 1:35:16
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class indexController extends baseController {

	public function index(){
		if(isset($_GET['mod'])){// 兼容上一版本的URL
			$this->_old($_GET['mod']);
		}

		$this->title = $_SESSION['website']['seotitle'];
		$this->keyword = $_SESSION['website']['keyword'];
		$this->description = $_SESSION['website']['description'];
//		$GLOBALS['block'] = 'aaaa';

		$this->display();
	}


	private function _old($mod){
		// 将当前的$_GET变量构造为要重定向的页面的参数
		$query = '';

		unset($_GET['mod']);	// 删除mod参数
		foreach($_GET as $key => $value){
			$query .= '&'. $key .'='. $value;
		}

		if($query){
			$query = '?'. substr($query, 1);
		}

		switch ($mod){
			case 'index':
			case 'album':
			case 'mood':
			case 'item':
			case 'intro':
				redirect('/'. $mod . $query);
				break;

			case 'article_list':
				redirect('/article'. $query);
				break;

			case 'article_read':
				redirect('/article/read'. $query);
				break;

			case 'album_view':
				redirect('/album/view'. $query);
				break;

			default :
				return ;

		}
	}
}