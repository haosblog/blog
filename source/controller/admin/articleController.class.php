<?php
/**
 * File: articleController.class.php
 * Created on : 2014-11-17, 0:41:15
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class articleController extends controller {

	public function __construct($router = array()) {
		parent::__construct($router);
		$this->buffer['nav'] = 'article';
	}

	/**
	 * 编辑/新增文章
	 */
	public function edit(){
		$aid = intval($_GET['aid']);
		if($aid){
			$this->buffer['article'] = M('article')->getByAid($aid);
		}

		$this->buffer['category'] = M('category')->field('cid', 'catname')->select();

		$this->display();
	}

	public function article(){
		$content = $this->_parseContent($_POST['content']);

	}

	private function _parseContent($content){
		$pregMatchArr = $loopingData = array();
		preg_match_all("/\{\{loop .*?from=\"(.*?)\"\}\}(.*?)\{\{\/loop\}\}/is", $content, $pregMatchArr);

		foreach($pregMatchArr[0] as $key => $val){
			$fieldName = $pregMatchArr[1][$key];
			$block  = $pregMatchArr[2][$key];

			if(!isset($loopingData)){
				$data = $this->_getCategoryData($fieldName);
				if($data === FALSE){
					continue;
				}

				$loopingData[$fieldName] = $data;
			}

			$content = $this->_replaceLoop($content, $val, $block, $loopingData[$fieldName]);
		}
	}
}
