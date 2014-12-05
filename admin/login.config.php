<?php

//@ validate inclusion
if(!defined('VALID_ACL_')) exit('direct access is not allowed.');

define('USEDB',			true);				//@ use database? true:false
define('LOGIN_METHOD',	'user');			//@ 'user':'email','both'
define('SUCCESS_URL',	'index.php?op=home');		//@ redirection target on success

//@ you could delete one of block below according to the USEDB value
if(USEDB) 
	{
		include("../config.inc.php");
		$db_config = array(
				'server'	=>	$config['server'],	//@ server name or ip address along with suffix ':port' if needed (localhost:3306)
				'user'		=>	$config['user'],			//@ mysql username
				'pass'		=>	$config['pass'],	//@ mysql password
				'name'		=>	$config['database'],		//@ database name
				'tbl_user'	=>	$config['tablePrefix'].'tbl_user'		//@ user table name
			);
	}
else
	{
		$user_config = array(
			array(
				'username'	=>	'',
				'useremail'	=>	'',
				'userpassword'	=>	'',	// md5
			),
			array(
				'username'	=>	'',
				'useremail'	=>	'',
				'userpassword'	=>	'',	// md5
			)	
		);
	}
?>