<?php
header("Content-type: text/html; charset=utf-8");

return array (
	'DB_HOST' => 'localhost',
	'DB_USER' => 'root',
	'DB_PSWD' => '',
	'DB_NAME' => 'haosblog',
	'DB_PRE' => 'hao_',
	'WEBROOT' => '/',

	'GROUP' => array(
		'admin' => array(
			'path' => 'admin',
			'template' => 'admin'
		),
	)
);