<?php
/**
 * File: error.class.php
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 错误输出类
 */

abstract class error {
	public static function trhow($msg){
		die($msg);
	}
}