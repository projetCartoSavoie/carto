<?php

namespace Carto\LivreOrBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	public function testIndexAfficheCommentaires()
	{
		$client = static::createClient();

		$crawler = $client -> request('GET', '/en/livre-d-or/');

		$this -> assertTrue($crawler->filter('html:contains("Leave a comment")') -> count() > 0,'problème affichage du livre d\'or');
		$this -> assertTrue($crawler->filter('html:contains("Author")') -> count() > 0,'problème affichage du formulaire');
	}

	public function testIndexEnregistreCommentaires()
	{
		$client = static::createClient();

		$crawler = $client -> request('GET', '/en/livre-d-or/');

		$form = $crawler -> selectButton('submit') -> form();
		$form['form[auteur]'] = 'testeur';
		$form['form[contenu]'] = 'Mon nouveau commentaire';
		$crawler = $client -> submit($form);

		$this -> assertTrue($crawler->filter('html:contains("Mon nouveau commentaire")') -> count() > 0,'problème enregistrement commentaire livre d\'or');
	}

	public function testIndexSupprimeCommentaires()
	{
		$client = static::createClient();

		$crawler = $client -> request('GET', '/en/donnees/admin');

		$crawler = $client -> followRedirect();

		$form = $crawler -> selectButton('submit') -> form();
		$form['_username'] = 'admin';
		$form['_password'] = 'adminpass';
		$crawler = $client->submit($form);

		$crawler = $client -> followRedirect();

		//echo substr($client -> getResponse() -> getContent(),0,20000);
		$crawler = $client -> request('GET', '/en/livre-d-or/admin');

		$link = $crawler -> selectLink('Supprimer') -> link();
		$crawler = $client -> click($link);

		$this -> assertTrue($crawler->filter('html:contains("Mon nouveau commentaire")') -> count() == 0,'problème suppression commentaire livre d\'or');
	}
}
