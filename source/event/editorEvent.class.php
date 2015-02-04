<?php
/**
 * File: editorEvent.class.php
 * Created on : 2014-11-24, 23:47:16
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 编辑器语法解析类
 */

class editorEvent extends controller {

	private $_titleArr = array();

	private $_lastList = array();

	public function parseContent($content){
		$content = htmlspecialchars($content, 3);
		// 按行分解内容
		$lineArr = explode("\n", $content);
		$htmlContent = '';

		foreach($lineArr as $line){
			$getList = false;
			if(strpos($line, '\'\'\'') !== FALSE){// 三引号，解析加粗
				$line = $this->_parseBlod($line);
			}

			if(strpos($line, '\'\'') !== FALSE){// 二引号，解析倾斜
				$line = $this->_parseItalic ($line);
			}

			if(strpos($line, '[[') !== FALSE){// 二引号，解析倾斜
				$line = $this->_parseLink($line);
			}

			$firstLetter = substr($line, 0, 1);
			switch ($firstLetter){
				case '#':
				case '*':
					$line = $this->_parseList($line);
					$getList = true;
					break;

				case '=':
					$line = $this->_parseTitle($line);
					break;

				default:
					$line = '<p>'. $line .'</p>';

			}

			$htmlContent .= $line;
			if(!$getList){
				$this->_lastList = array();
				if(!empty($this->_lastList)){// 如果本行没有列表标记，但上一行存在，则闭合
					$htmlContent .= $this->_closeList(0);
				}
			}
		}

		if(!empty($this->_lastList)){// 循环结束，但列表尚未被闭合，则闭合
			$htmlContent .= $this->_closeList(0);
		}

		return$htmlContent;
	}

	private function _parseBlod($content){
		$pregMatchArr = $loopingData = array();
		preg_match_all("/'''(.*?)'''/is", $content, $pregMatchArr);

		foreach($pregMatchArr[0] as $key => $val){
			$text = $pregMatchArr[1][$key];
			$html = '<strong>'. $text .'</strong>';

			$content = str_replace($val, $html, $content);
		}

		return $content;
	}

	private function _parseItalic($content){
		$pregMatchArr = $loopingData = array();
		preg_match_all("/''(.*?)''/is", $content, $pregMatchArr);

		foreach($pregMatchArr[0] as $key => $val){
			$text = $pregMatchArr[1][$key];
			$html = '<i>'. $text .'</i>';

			$content = str_replace($val, $html, $content);
		}

		return $content;
	}

	private function _parseLink($content){
		$pregMatchArr = $loopingData = array();
		preg_match_all("/\[\[(.*?)\]\]/is", $content, $pregMatchArr);

		foreach($pregMatchArr[0] as $key => $val){
			$text = $pregMatchArr[1][$key];
			if(strpos(strtolower($text), 'image:') === 0){// 调用图片
				$html = $this->_parseImage($text);
			} elseif(is_numeric($text)){// 纯数字，读取文章链接
				$html = $this->_getArticleLink($text);
			} elseif(strpos($text, '|') !== false) {
				list($link, $node) = explode('|', $text, 2);
				$html = '<a href="'. $link .'" target="_blank">'. $node .'</a>';
			} else {
				continue;
			}

			if(!$html){
				continue;
			}

			$content = str_replace($val, $html, $content);
		}

		return $content;
	}


	private function _parseList($content){
		$listInfo = array();
		$len = strlen($content);
		for($i = 0; $i < $len; $i++){
			$letter = substr($content, $i, 1);
			if($letter == ' '){
				continue;
			}

			if($letter == '#' || $letter == '*'){
				$listInfo['type'][] = $letter;
			} else {
				break;
			}
		}

		$lineContent = trim(substr($content, $i));	// 剔除前面的符号后的行内容
		$thisLineLen = $listInfo['count'] = count($listInfo['type']);

		if(!empty($this->_lastList)){
			$lastLineLen = $this->_lastList['count'];
			$up = max($thisLineLen, $lastLineLen);
			$different = null;

			for($i = 0; $i < $up; $i++){
				if($listInfo['type'][$i] != $this->_lastList['type'][$i]){
					$different = $i;
					break;
				}
			}
			if($different >= $lastLineLen){// 如果差异点位于上一行的标记之后，即本行标记比上行标记多，则不需闭合直接新增
				$return = $this->_addList($listInfo['type'], $lineContent, $different);
			} elseif(is_null($different)) {// 如果different为空，说明不存在差异点，直接新增li
				$return = "\n<li>". $lineContent .'</li>';
			} else {// 存在差异点且差异点处于上一行列表的中段，先闭合上段列表再新增
				$return = $this->_closeList($different);
				$return .= $this->_addList($listInfo['type'], $lineContent, $different);
			}
		} else {// 上一行不存在列表，则直接输出列表
			$return = $this->_addList($listInfo['type'], $lineContent, 0);
		}

		$this->_lastList = $listInfo;
		return $return;
	}

	private function _closeList($different){
		$return = '';
		$count = $this->_lastList['count'];
		for($i = $count - 1; $i >= $different; $i--){
			$tag = $this->_lastList['type'][$i];
			if($tag == '*'){
				$return .= '</ul>';
			} else {
				$return .= '</ol>';
			}

			if($i != 0){// 循环没有到行首，则增加</li>
				$return .= '</li>';
			}

			$return .= "\n";
		}

		return $return;
	}

	private function _addList($type, $lineContent, $star = 0){
		$len = count($type);
		$return = '';
		if($star != 0){// 从第0个开始，则不增加<li>标记
			$return .="<li>\n";
		}
		for($i = $star; $i < $len; $i++){
			if($type[$i] == '#'){
				$return .= '<ol>';
			} elseif($type[$i] == '*'){
				$return .= '<ul>';
			}

			$return .= "\n<li>";
		}

		$return .= $lineContent ."</li>\n";

		return $return;
	}

	private function _addLi(){

	}

	private function _parseTitle($content){
		$content = trim($content);
		$level = 0;
		// 标题等级最高到6（大于5），或本行长度的一半则跳出
		$levelUp = min(5, strlen($content) / 2);
		while(TRUE){
			$firstLetter = substr($content, $level, 1);
			$lastLetter = substr($content, -1 - $level, 1);

			if($firstLetter != '=' || $firstLetter != $lastLetter){
				break;
			}
			$level++;

			if($level > $levelUp){
				break;
			}
		}
		if($level > 1){
			$content = '<h'. $level .'>'. trim(substr($content, $level, -$level)) .'</h'. $level .'>';
		}

		return $content;
	}

	private function _parseImage($content){
		$pid = $info = substr($content, 6);
		if(strpos($info, '|') !== FALSE){// 如果有|符号，则切割获取参数，参数分别为：相片ID(或图片路径)、图片的显示位置、图片尺寸
			list($pid, $align, $size) = explode('|', $info);
		}


		$align = strtolower($align);
		if(!$align || !in_array($align, array('left', 'right', 'middle'))){
			$align = 'middle';
		}

		if(is_numeric($pid)){// pid为数字，则作为相册的ID处理，读取相册信息
			$photoInfo = M('photo')->field('pid', 'path')->getByPid($pid);
			if(!$photoInfo){ return false; }

			$large = $path = $photoInfo['path'];

			if(is_numeric($size)){// 未定义size，或size不为数字，则默认为500
				$size = 500;
			}
			$path = '/thumb/'. $size .'x0'. $path;	// 生成缩略图路径
		} else {// pid不为数字，则作为图片的URL处理
			$large = $path = $pid;
		}

		return '<div class="img_'. $align .'"><img data-large="'. $large .'" data-pid="'. $pid .'" src="'. $path .'" /></div>';
	}

	private function _getArticleLink($aid){
		$articleInfo = M('article')->field('aid', 'title')->getByAid($aid);
		if($articleInfo){
			return '<a href="/article/'. $articleInfo['aid'] .'" target="_blank">'. $articleInfo['title'] .'</a>';
		} else {
			return false;
		}
	}
}
