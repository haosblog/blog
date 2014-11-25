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

	public function parseContent($content){
		// 按行分解内容
		$lineArr = explode("\n", $content);
		$htmlContent = '';

		foreach($lineArr as $line){
			if(strpos($line, '\'\'\'') !== FALSE){// 三引号，解析加粗
				$line = $this->_parseBlod($line);
			}

			if(strpos($line, '\'\'') !== FALSE){// 二引号，解析倾斜
				$line = $this->_parseItalic ($line);
			}

			$firstLetter = substr($line, 0, 1);

			$htmlContent .= $line ."\n";
		}

		echo($htmlContent);
	}

	private function _parseBlod($content){
		$pregMatchArr = $loopingData = array();
		preg_match_all("/'''(.*?)'''/is", $content, $pregMatchArr);
		print_r($pregMatchArr);

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
		print_r($pregMatchArr);

		foreach($pregMatchArr[0] as $key => $val){
			$text = $pregMatchArr[1][$key];
			$html = '<i>'. $text .'</i>';

			$content = str_replace($val, $html, $content);
		}

		return $content;
	}
}
