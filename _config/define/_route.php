<?php

$routes = [
    'R_HOMEPAGE'        => '../public#home',
    'R_CONTACT'         => '../public#contact',
    'R_BLOG'            => '../public/index.php?p=post',
    'R_POST'            => '../public/index.php?p=post&id=',
    'R_LOGIN'           => '../public/index.php?p=login',
    'R_REGISTER'        => '../public/index.php?p=register',
    'R_LOGOUT'          => '../public/index.php?p=logout',
    'R_FORGOT_PASSWORD' => '../public/index.php?p=forgot-password',
];

$form = [
    'F_CONTACT'             => './index.php?p=contact',
    'F_COMMENT'             => './index.php?p=post&id=',
    'F_LOGIN'               => './index.php?p=login',
    'F_REGISTER'            => './index.php?p=register',
    'F_FORGOT_PASSWORD'     => './index.php?p=forgot-password',
    'F_RESET_PASSWORD'      => './index.php?p=reset-password',
];
