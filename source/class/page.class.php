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

	public $currentPage, $pageCount, $totalRow = 0, $link = '';
	//分页模板
	private $tmpl = '总共';


	public function __construct($currentPage, $pageCount, $total = 0, $link = '') {
		$this->currentPage = $currentPage;
		$this->pageCount = $pageCount;
		$this->totalRow = $total;
		$this->setLink($link);
	}


	public function setTemplat($tmpl){
		$this->tmpl = $tmpl;

		return $this;
	}

	public function setLink($link){
		$this->link = $link;
		$this->link .= (strpos($link, '?') === false ? '?' : '&') .'page=';

		return $this;
	}

	/**
	 * 构造
	 */
	public function build(){
		$totalPage = $this->totalPage();
		$str = '<span>共'. $totalPage .'页</span>';
		$str .= '<span>当前第'. $this->currentPage .'页</span>';
		$str .= '<a href="'. $this->link .'1">首页</span>';
		if($this->currentPage > 1){
			$str .= '<a href="'. $this->link . ($this->currentPage - 1) .'">上一页</span>';
		}

		if($this->currentPage < $totalPage){
			$str .= '<a href="'. $this->link . ($this->currentPage + 1) .'">下一页</span>';
		}
		$str .= '<a href="'. $this->link . $totalPage .'">末页</span>';

		return $str;
	}

	public function totalPage(){
		return ceil($this->totalRow / $this->pageCount);
	}
}
