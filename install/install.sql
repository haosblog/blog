DROP TABLE IF EXISTS `hao_catalogue`;
CREATE TABLE `hao_catalogue` (
	cid MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
	pid MEDIUMINT(5) NOT NULL DEFAULT '0',
	catname VARCHAR(30) NOT NULL,
	`type` TINYINT(1) NOT NULL DEFAULT '0',
	tfid SMALLINT(3) NOT NULL,
	arc_tfid SMALLINT(3) NOT NULL,
	`count` MEDIUMINT(5) NOT NULL DEFAULT '0',
	cover VARCHAR(30),
	`path` VARCHAR(30) NOT NULL,
	mid SMALLINT(3) NOT NULL,
	title VARCHAR(255),
	keyword VARCHAR(255),
	description TEXT,
	arc_title VARCHAR(255),
	arc_keyword VARCHAR(255),
	arc_description TEXT,
	PRIMARY KEY(`cid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_module`;
CREATE TABLE `hao_module` (
	mid SMALLINT(3) NOT NULL AUTO_INCREMENT,
	modname VARCHAR(10) NOT NULL,
	tablename VARCHAR(10) NOT NULL,
	wsid VARCHAR(100) NOT NULL DEFAULT '0',
	classable TINYINT(1) NOT NULL DEFAULT '1',
	commentable TINYINT(1) NOT NULL DEFAULT '1',
	classname VARCHAR(15),
	listname VARCHAR(15),
	createname VARCHAR(15),
	PRIMARY KEY(`mid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_module_field`;
CREATE TABLE `hao_module_field` (
	fid MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
	mid SMALLINT(3)	NOT NULL,
	fieldname VARCHAR(15) NOT NULL,
	viewname VARCHAR(20) NOT NULL,
	fieldtype TINYINT(2) NOT NULL DEFAULT '0',
	length MEDIUMINT(5),
	formtype TINYINT(2) DEFAULT '0',
	`default` VARCHAR(255),
	linebreak TINYINT(1) NOT NULL DEFAULT '0',
	allowhtml TINYINT(1) NOT NULL,
	PRIMARY KEY(`fid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_user`;
CREATE TABLE `hao_user` (
	uid SMALLINT(3) NOT NULL AUTO_INCREMENT,
	username VARCHAR(20) NOT NULL,
	`password` VARCHAR(32) NOT NULL,
	`type` TINYINT(1) NOT NULL,
	wslist VARCHAR(30) NOT NULL DEFAULT '0',
	PRIMARY KEY(`uid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `hao_website`;
CREATE TABLE `hao_website` (
	wsid SMALLINT(3) NOT NULL AUTO_INCREMENT,
	websitename VARCHAR(50) NOT NULL,
	seotitle VARCHAR(255),
	keyword VARCHAR(255),
	description TEXT,
	isdefault TINYINT(1) NOT NULL DEFAULT '0',
	`type` TINYINT(1) NOT NULL DEFAULT '0',
	tpid SMALLINT(3) NOT NULL DEFAULT '1',
	PRIMARY KEY(`wsid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_domain`;
CREATE TABLE `hao_domain` (
	did SMALLINT(3) NOT NULL AUTO_INCREMENT,
	wsid SMALLINT(3) NOT NULL,
	`domain` VARCHAR(80) NOT NULL,
	PRIMARY KEY(`did`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_template`;
CREATE TABLE `hao_template` (
	tpid SMALLINT(3) NOT NULL AUTO_INCREMENT,
	templatename VARCHAR(20) NOT NULL,
	`path` VARCHAR(50) NOT NULL,
	PRIMARY KEY(`tpid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_template_file`;
CREATE TABLE `hao_template_file` (
	tfid SMALLINT(3) NOT NULL AUTO_INCREMENT,
	tpid SMALLINT(3) NOT NULL,
	filename VARCHAR(15) NOT NULL,
	viewname VARCHAR(30) NOT NULL,
	`type` TINYINT(1) NOT NULL,
	PRIMARY KEY(`tfid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_comment`;
CREATE TABLE `hao_comment` (
	cmid MEDIUMINT(6) NOT NULL AUTO_INCREMENT,
	username VARCHAR(30) NOT NULL,
	portrait SMALLINT(2) NOT NULL DEFAULT '1',
	sex ENUM('m', 'f') NOT NULL DEFAULT 'm',
	title VARCHAR(30),
	content TEXT NOT NULL,
	`time` INT(10) NOT NULL DEFAULT '0',
	`type` TINYINT(2) NOT NULL DEFAULT '0',
	fid MEDIUMINT(8) NOT NULL DEFAULT '0',
	reply MEDIUMINT(6) NOT NULL DEFAULT '0',
	email VARCHAR(80),
	ip VARCHAR(15),
	PRIMARY KEY(`cmid`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE EXISTS `hao_album`;
CREATE TABLE `hao_album` (
	`abid` MEDIUMINT(6) NOT NULL AUTO_INCREMENT ,
	`albumname` VARCHAR( 30 ) NOT NULL ,
	`cover` VARCHAR( 255 ) NOT NULL default '',
	`path` VARCHAR( 30 ) NOT NULL default '',
	`intro` TEXT NOT NULL ,
	`password` VARCHAR( 50 ) default NULL ,
	`pwclew` VARCHAR( 255 ) default NULL ,
	`wsid` SMALLINT(3) NOT NULL,
	PRIMARY KEY ( `abid` )
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE `haoami`.`bbb` (
	`pid` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
	`abid` MEDIUMINT(6) NOT NULL DEFAULT '0',
	`title` VARCHAR( 30 ) NOT NULL ,
	`path` VARCHAR( 255 ) NOT NULL ,
	`intro` TEXT NOT NULL ,
	`aid` MEDIUMINT( 5 ) NOT NULL default '0',
	`addtime` INT( 10 ) NOT NULL default '0',
	`wsid` SMALLINT(3) NOT NULL,
	PRIMARY KEY ( `id` )
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hao_cache`;
CREATE TABLE `hao_cache` (
	cachename VARCHAR(100) NOT NULL,
	cachedata TEXT NOT NULL,
	PRIMARY KEY (`cachename`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;