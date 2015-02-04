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
		$this->_intro();
	}

	private function _article(){
		$page = 1;
		while(true){
			$list = M('old_article')->page($page, 30)->select();
			if(!$list){
				break;
			}

			$addData = array();

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


				$this->translate($tmp, $item['content']);

				$addData[] = $tmp;
			}

			M('article')->multiInsert($addData);
			$page++;
		}
		echo('article success');
	}

	private function _category(){
		$list = M('old_class')->where(array('status' => 1))->select();

		$addData = array();
		foreach($list as $item){
			$tmp = array(
				'cid' => $item['id'],
				'catname' => $item['name'],
				'wsid' => ($item['master'] == 'hao' ? 1 : 2),
			);

			$tmp['count'] = M('article')->where(array('cid' => $item['id']))->count();

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

	private function _intro(){
		$list = M('old_intro')->select();

		$addData = array();
		foreach($list as $item){
			$tmp = array(
				'id' => $item['id'],
				'img' => $item['img'],
				'time' => $item['time'],
				'wsid' => ($item['master'] == 'hao' ? 1 : 2),
			);
			$this->translate($tmp, $item['content']);

			$addData[] = $tmp;
		}
		M('intro')->multiInsert($addData);

		echo('intro success');
	}



	private function translate(&$data, $content){
		$str = str_replace('<br />&nbsp;&nbsp;&nbsp;&nbsp;', "\n", $content);
		$str = str_replace("&nbsp;", " ", $str);
		$str = str_replace("&emsp;&emsp;", "	", $str);
		$str = str_replace("&#9;", "	", $str);

		$str = str_replace("<a href=\"",'[[',$str);
		$str = str_replace("\" target=\"_blank\">",'|',$str);
		$str = str_replace('</a>', ']]', $str);
		$str = str_replace('<div class="pic"><img src="', '[[image:/thumb/800x0/data/upload/', $str);
		$str = str_replace('" class="img" /></div>', ']]', $str);
		$str = stripslashes($str);

		$editEvent = new editorEvent();
		$data['content_ori'] = substr($str, 4);
		$data['content'] = $editEvent->parseContent($data['content_ori']);
	}
}
