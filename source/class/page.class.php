<?php
/**
 * File: page.class.php
 * Created on : 2015-1-4, 1:14:08
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 生成分页链接类
 */

class page {

	public $currentPage, $totalPage, $totalRow = 0, $link = '';
	//分页模板
	private $tmpl = '总共';


	public function __construct($currentPage, $totalPage, $link = '', $total = 0) {
		$this->currentPage = $currentPage;
		$this->totalPage = $totalPage;
		$this->totalRow = $total;
		$this->link = $link;
	}


	public function setTemplat($tmpl){
		$this->tmpl = $tmpl;

		return $this;
	}

	/**
	 * 构造
	 */
	public function build(){

	}
}
