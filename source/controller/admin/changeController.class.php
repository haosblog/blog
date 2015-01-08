<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2015-1-8 18:53:03
 * 
 * 请在此处输入文件注释
 */

class changeController extends controller {

	public function index(){
		set_time_limit(0);
		$this->_article();
		$this->_category();
		$this->_album();
		$this->_photo();
		$this->_mood();
	}
	
	private function _article(){
		$page = 1;
		while(true){
			$list = M('old_article')->page($page, 30)->select();
			if(!$list){
				break;
			}

			$addData = array();
			$editEvent = new editorEvent();

			foreach($list as $item){
				$tmp = array(
					'aid' => $item['id'],
					'cid' => $item['cid'],
					'title' => $item['title'],
	//				'content_ori' => $item['content'],
					'original' => ($item['aut'] == 0 ? 1 : 0),
					'fromurl' => $item['aut_url'],
					'status' => $item['state'],
					'viewcount' => $item['read'],
					'repostcount' => $item['review'],
					'wrtime' => $item['wrtime'],
					'retime' => $item['retime'],
					'chtime' => $item['chtime'],
					'sina_weibo_id' => $item['sina_weibo_id'],
					'wsid' => ($item['master'] == 'hao' ? 1 : 2),
					'oldid' => $item['oldid'],
				);


				$str = str_replace('<br />&nbsp;&nbsp;&nbsp;&nbsp;', "\n", $item['content']);
				$str = str_replace("&nbsp;", " ", $str);
				$str = str_replace("&emsp;&emsp;", "	", $str);
				$str = str_replace("&#9;", "	", $str);

				$str = str_replace("<a href=\"",'[[',$str);
				$str = str_replace("\" target=\"_blank\">",'|',$str);
				$str = str_replace('</a>', ']]', $str);
				$str = str_replace('<div id="pic" ><img src="', '[[image:', $str);
				$str = str_replace('" class="img" /></div>', ']]', $str);
				$str = stripslashes($str);

				$tmp['content_ori'] = substr($str, 4);
				$tmp['content'] = $editEvent->parseContent($tmp['content_ori']);

				$addData[] = $tmp;
			}

			M('article')->multiInsert($addData);
			$page++;
		}
		echo('article success');
	}
	
	private function _category(){
		$list = M('old_class')->select();
		
		$addData = array();
		foreach($list as $item){
			$tmp = array(
				'cid' => $item['id'],
				'catname' => $item['name'],
				'wsid' => ($item['master'] == 'hao' ? 1 : 2),
			);
			
			$addData[] = $tmp;
		}
		M('category')->multiInsert($addData);
		
		echo('category success');
	}
	
	private function _album(){
		$list = M('old_album')->select();
		
		$addData = array();
		foreach($list as $item){
			$tmp = array(
				'aid' => $item['id'],
				'name' => $item['name'],
				'intro' => $item['intro'],
				'cover_path' => $item['cover_path'],
				'password' => $item['password'],
				'clew' => $item['clew'],
				'time' => 0,
				'wsid' => ($item['master'] == 'hao' ? 1 : 2),
			);
			
			$addData[] = $tmp;
		}
		M('album')->multiInsert($addData);
		
		echo('album success');
	}
	
	private function _photo(){
		$list = M('old_photo')->select();
		
		$addData = array();
		foreach($list as $item){
			$tmp = array(
				'pid' => $item['id'],
				'aid' => $item['aid'],
				'title' => $item['title'],
				'summary' => $item['intro'],
				'path' => $item['path'],
				'time' => $item['time'],
				'wsid' => ($item['master'] == 'hao' ? 1 : 2),
			);
			
			$addData[] = $tmp;
		}
		M('photo')->multiInsert($addData);
		
		echo('photo success');
	}
	
	private function _mood(){
		$list = M('old_mood')->select();
		
		$addData = array();
		foreach($list as $item){
			$tmp = array(
				'id' => $item['id'],
				'content' => $item['content'],
				'dateline' => $item['time'],
				'wbid' => $item['mid'],
				'wsid' => ($item['master'] == 'hao' ? 1 : 2),
			);
			
			$addData[] = $tmp;
		}
		M('mod_mood')->multiInsert($addData);
		
		echo('mood success');
	}
}