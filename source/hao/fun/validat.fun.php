<?php

/**
 * File: Validate.php
 * Functionality: Extra validate functions
 * Author: Nic XIE
 * Date: 2012-03-01
 */
// Check var is a valid email or not
function isEmail($email) {
	if (!$email) {
		return false;
	}

	return preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/', $email);
}

// Check var is a valid Chinese mobile or not
function isMobile($mobile) {
	if (!$mobile) {
		return false;
	}

	return preg_match('/^((\(d{2,3}\))|(\d{3}\-))?1(3|5|8|9)\d{9}$/', $mobile);
}

// Check var is a valid postal code or not
function isPostalCode($postalCode) {
	if (!$postalCode) {
		return false;
	}

	return preg_match("/^[1-9]\d{5}$/", $postalCode);
}

// Check var is a valid IP Address or not
function isIPAddress($IPAddress) {
	if (!$IPAddress) {
		return false;
	}

	return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" .
			"(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $IPAddress);
}

// Check var is a valid ID card or not
function isIDCard($IDCard) {
	if (!$IDCard) {
		return false;
	}

	return preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/', $IDCard);
}

/**
 * �������
 * @param string $str ��ǩ�ַ���
 */
function isCn($str) {
	if (preg_match("/[\x{4e00}-\x{9fa5}]+/u", $str)) {
		return true;
	}
	return false;
}

/**
 * �������
 * @param string $str ��ǩ�ַ���
 */
function isNumber($str) {
	if (preg_match('/^\d+$/', $str)) {
		return true;
	}
	return false;
}

/**
 * ����Ƿ�ÿλ��ͬ
 * @param string $str ��ǩ�ַ���
 */
function isNumSame($str) {
	if (preg_match('/^(\w)\1+$/', $str)) {
		return true;
	}
	return false;
}

/**
 * ����Ƿ�Ϊ��
 * @param string $str ��ǩ�ַ���
 */
function isEmpty($str) {
	//$str = trim($str);
	if (preg_match('/^\s*$/', $str)) {
		return true;
	}
	return false;
}
