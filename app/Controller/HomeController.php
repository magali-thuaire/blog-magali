<?php

namespace App\Controller;

class HomeController extends AppController
{
	public function index()
	{
		$this->render('homepage.index');
	}

	public function newContact()
	{
		// TODO: validation du formulaire (affichage des messages : réussite ou échec)
		// TODO: enregistrement en BDD
		// TODO: envoi du mail

		$this->render('homepage.index');

	}

}