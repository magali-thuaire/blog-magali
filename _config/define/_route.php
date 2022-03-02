<?php

$routes = [
    'R_HOMEPAGE'                        => '../public#home',
    'R_CONTACT'                         => '../public#contact',
    'R_BLOG'                            => '../public/index.php?p=post',
    'R_POST'                            => '../public/index.php?p=post&id=',
    'R_LOGIN'                           => '../public/index.php?p=login',
    'R_REGISTER'                        => '../public/index.php?p=register',
    'R_LOGOUT'                          => '../public/index.php?p=logout',
    'R_FORGOT_PASSWORD'                 => '../public/index.php?p=forgot-password',
    'R_ADMIN'                           => '../public/admin.php?p=dashboard',
    'R_ADMIN_POST_CONFIRM_DELETE'       => '../public/admin.php?p=post-confirm-delete&id=',
    'R_ADMIN_POST_DELETE'               => '../public/admin.php?p=post-delete&id=',
    'R_ADMIN_POST_UPDATE'               => '../public/admin.php?p=post-update&id=',
    'R_ADMIN_POST_NEW'                  => '../public/admin.php?p=post-new',
    'R_ADMIN_POST_SHOW'                 => '../public/admin.php?p=post&id=',
    'R_ADMIN_COMMENT'                   => '../public/admin.php?p=comment',
    'R_ADMIN_COMMENT_CONFIRM_APPROVE'   => '../public/admin.php?p=comment-confirm-approve&id=',
    'R_ADMIN_COMMENT_APPROVE'           => '../public/admin.php?p=comment-approve&id=',
    'R_ADMIN_COMMENT_CONFIRM_DELETE'    => '../public/admin.php?p=comment-confirm-delete&id=',
    'R_ADMIN_COMMENT_DELETE'            => '../public/admin.php?p=comment-delete&id=',
    'R_ADMIN_USER'                      => '../public/admin.php?p=user',
    'R_ADMIN_USER_CONFIRM_VALIDATE'     => '../public/admin.php?p=user-confirm-validate&id=',
    'R_ADMIN_USER_VALIDATE'             => '../public/admin.php?p=user-validate&id=',
    'R_ADMIN_USER_CONFIRM_DELETE'       => '../public/admin.php?p=user-confirm-delete&id=',
    'R_ADMIN_USER_DELETE'               => '../public/admin.php?p=user-delete&id=',
];

$form = [
    'F_CONTACT'             => './index.php?p=contact',
    'F_COMMENT'             => './index.php?p=post&id=',
    'F_LOGIN'               => './index.php?p=login',
    'F_REGISTER'            => './index.php?p=register',
    'F_FORGOT_PASSWORD'     => './index.php?p=forgot-password',
    'F_RESET_PASSWORD'      => './index.php?p=reset-password',
];
