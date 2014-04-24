<?php

namespace Carto\DonneesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

	public function testLogin()
	{
		$client = static::createClient();
		$crawler = $client -> request('GET', '/en/donnees/admin/enregistrer');

		$crawler = $client->followRedirect();

		$form = $crawler -> selectButton('submit') -> form();
		$form['_username'] = 'admin';
		$form['_password'] = 'adminpass';
		$crawler = $client->submit($form);

		$crawler = $client->followRedirect();

		//echo $client -> getResponse() -> getContent();

		//test bidon
		$this->assertTrue($crawler->filter('html:contains("pour enregistrer le contenu de wordnet")')-> count() > 0,'mauvais accès en zone admin');
	}
}
