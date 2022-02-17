<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

class AdminPostController extends AppController
{
    public function index()
    {
        $posts = $this->postManager->findAllByAuthorOrderedByNewest($this->getUser());

        $this->render('admin/post/index.twig', [
            'posts' => $posts
        ]);
    }
}