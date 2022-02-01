<?php

namespace App\Controller;

class HomeController extends AppController
{
	public function index()
	{
		$this->render('homepage.index');
	}
}