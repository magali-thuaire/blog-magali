<?php

$routes = [
	'R_HOMEPAGE'	=> '../public#home',
	'R_CONTACT'	 	=> '../public#contact',
	'R_BLOG'	 	=> '../public/index.php?p=post',
	'R_POST'	 	=> '../public/index.php?p=post&id=',
	'R_LOGIN'	 	=> '../public/index.php?p=login',
];

$form = [
	'F_CONTACT'	 	=> './index.php?p=contact',
	'F_COMMENT'	 	=> './index.php?p=post&id=',
	'F_LOGIN'	 	=> './index.php?p=login',
];
