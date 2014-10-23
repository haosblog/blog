<?php
header("Content-type: text/html; charset=utf-8");

return array (
	'dbhost' => 'localhost',
	'dbuser' => 'root',
	'dbpswd' => '',
	'dbname' => 'haosblog',
	'tbpre' => 'hao_',
	'webroot' => '/',

	'group' => array(
		'admin' => array(
			'path' => 'admin',
			'template' => 'admin'
		),
	)
);