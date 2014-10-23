<?php
$ogamehost = 'ogame.42sfw.com';
if(isset($_GET['token'])){
	$filename = './data/sfw/ogame/'. $_GET['token'] .'.txt';
	$uid = file_get_contents($filename);
	//unlink($filename);
	die($uid);
}

$method = $_GET['method'];
if(isset($method)){
	if($method == 'getusername' && isset($_GET['uid'])){
		$uid = intval($_GET['uid']);
		$userdata = DB::fetch_first("SELECT username FROM pre_common_member WHERE uid='$uid'");
		if($userdata){
			die($userdata['username']);
		} else {
			die('0');
		}
	}
}

if($_G['uid'] > 0){
	$md5 = md5($_G['uid'] . time());
	$filename = './data/sfw/ogame/'. $md5 .'.txt';
	file_put_contents($filename, $_G['uid']);
	header('Location: http://'. $ogamehost .'/login.php?token='. $md5);
} else {
	showmessage('ÇëµÇÂ¼»ÃÓÑÖ®¼Ò', '', array(), array('login' => true));
}