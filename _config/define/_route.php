<?php

use App\Controller\Admin\AdminCommentController;
use App\Controller\Admin\AdminPostController;
use App\Controller\Admin\AdminUserController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Controller\SecurityController;

return [
//CLIENT
    'homepage'                  => ['ALL', HomeController::class, ['index']],
    'contact'                   => ['ALL', HomeController::class, ['index'], ['newContact']],
    'post'                      => ['ALL', PostController::class, ['show', ['id'], 'index'], ['newComment', ['id']]],
    'login'                     => ['ALL', SecurityController::class, ['login'], ['authenticate']],
    'register'                  => ['ALL', SecurityController::class, ['signin'], ['register']],
    'logout'                    => ['ALL', SecurityController::class, ['logout']],
    'validate'                  => ['ALL', SecurityController::class, ['validate', ['email', 'token']]],
    'forgot-password'           => ['ALL', SecurityController::class, ['forgotPassword'], ['emailPassword']],
    'reset-password'            => ['ALL', SecurityController::class, ['newPassword', ['email', 'token']], ['resetPassword']],
//ADMIN
    'dashboard'                 => ['ADMIN', AdminPostController::class, ['index']],
    'post-confirm-delete'       => ['ADMIN', AdminPostController::class, ['confirmDelete', ['id'], 'index']],
    'post-delete'               => ['ADMIN', AdminPostController::class, ['index'], ['delete', ['id']]],
    'post-update'               => ['ADMIN', AdminPostController::class, ['change', ['id'], 'index'], ['update', ['id']]],
    'post-new'                  => ['ADMIN', AdminPostController::class, ['create'], ['new']],
    'admin-post'                => ['ADMIN', AdminPostController::class, ['show', ['id'], 'index']],
    'comment'                   => ['ADMIN', AdminCommentController::class, ['index']],
    'comment-confirm-approve'   => ['ADMIN', AdminCommentController::class, ['confirmApprove', ['id'], 'index']],
    'comment-approve'           => ['ADMIN', AdminCommentController::class, ['index', ['id']], ['approve', ['id'], 'index']],
    'comment-confirm-delete'    => ['ADMIN', AdminCommentController::class, ['confirmDelete', ['id'], 'index']],
    'comment-delete'            => ['ADMIN', AdminCommentController::class, ['index', ['id']], ['delete', ['id']]],
//SUPERADMIN
    'user'                      => ['SUPERADMIN', AdminUserController::class, ['index']],
    'user-confirm-validate'     => ['SUPERADMIN', AdminUserController::class, ['confirmValidate', ['id'], 'index']],
    'user-validate'             => ['SUPERADMIN', AdminUserController::class, ['index', ['id']],['validate', ['id']]],
    'user-confirm-delete'       => ['SUPERADMIN', AdminUserController::class, ['confirmDelete', ['id'], 'index']],
    'user-delete'               => ['SUPERADMIN', AdminUserController::class, ['index', ['id']], ['delete', ['id']]],
    'user-update'               => ['SUPERADMIN', AdminUserController::class, ['change', ['id'], 'index'], ['update', ['id']]],
];