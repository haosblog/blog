<?php

/**
 * File: $(name)
 * Created on : 2013-12-12, 0:42:31
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 自动验证类
 */


class validat {
	public static function checkStr($str, $rule){
		$ruleArr = self::parseRule($rule);
		$null = false;

		foreach($ruleArr as $item){
			switch ($item){
				case 'null' :
					$null = true;
					break;
				case 'email' :
					break;
			}
		}
	}

	public static function parseRule($rule){
		if(empty($rule)){
			return array();
		} elseif(strpos($rule, '|') === false) {
			return array($rule);
		} else {
			return explode('|', $rule);
		}
	}

	// Check var is a valid email or not
	public static function isEmail($email) {
		if (!$email) {
			return false;
		}

		return preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/', $email);
	}

	// Check var is a valid Chinese mobile or not
	public static function isMobile($mobile) {
		if (!$mobile) {
			return false;
		}

		return preg_match('/^((\(d{2,3}\))|(\d{3}\-))?1(3|5|8|9)\d{9}$/', $mobile);
	}

	// Check var is a valid postal code or not
	public static function isPostalCode($postalCode) {
		if (!$postalCode) {
			return false;
		}

		return preg_match("/^[1-9]\d{5}$/", $postalCode);
	}

	// Check var is a valid IP Address or not
	public static function isIPAddress($IPAddress) {
		if (!$IPAddress) {
			return false;
		}

		return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" .
				"(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $IPAddress);
	}

	// Check var is a valid ID card or not
	public static function isIDCard($IDCard) {
		if (!$IDCard) {
			return false;
		}

		return preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/', $IDCard);
	}

	/**
	 * 检查中文
	 * @param string $str 标签字符串
	 */
	public static function isCn($str) {
		if (preg_match("/[\x{4e00}-\x{9fa5}]+/u", $str)) {
			return true;
		}
		return false;
	}

	/**
	 * 检查数字
	 * @param string $str 标签字符串
	 */
	public static function isNumber($str) {
		if (preg_match('/^\d+$/', $str)) {
			return true;
		}
		return false;
	}

	/**
	 * 检查是否每位相同
	 * @param string $str 标签字符串
	 */
	public static function isNumSame($str) {
		if (preg_match('/^(\w)\1+$/', $str)) {
			return true;
		}
		return false;
	}
}