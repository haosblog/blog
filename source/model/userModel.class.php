<?php

/**
 * copyright 小皓 (C)2013-2099 版权所有
 *
 * 用户模型
 */
class userModel extends model {
	public function cookieLogin($cookie){
		if(!$cookie){
			return false;
		}

		$userdata = $this->getByCookie($cookie);

		return $userdata;
	}
}
?>