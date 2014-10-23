<?php
/**
 * File: index.php
 * Created on : 2014-1-20, 23:59:06
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * MVC结构插件入口
 */

require 'hao_core.class.php';
define('HAO_ROOT', DISCUZ_ROOT .'./source/hao/');
hao_core::init($extend);