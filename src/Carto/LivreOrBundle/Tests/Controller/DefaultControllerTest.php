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

	/*public function testIndexEnregistreCommentaires()
	{
		$client = static::createClient();

		$crawler = $client -> request('GET', '/en/livre-d-or/');

		$form = $crawler -> selectButton('submit') -> form();
		$form['form[auteur]'] = 'testeur';
		$form['form[contenu]'] = 'Mon nouveau commentaire';
		$crawler = $client -> submit($form);
		echo $client->getResponse()->getContent();

		$this -> assertTrue($crawler->filter('html:contains("Mon nouveau commentaire")') -> count() > 0,'problème enregistrement commentaire livre d\'or');
	}

	public function testIndexSupprimeCommentaires()
	{
		$client = static::createClient();

		$crawler = $client -> request('GET', '/en/livre-d-or/');

		$this -> assertTrue($crawler->filter('html:contains("Mon nouveau commentaire")') -> count() == 0,'problème suppression commentaire livre d\'or');
	}*/
}
