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

		$this->assertTrue($crawler->filter('html:contains("Welcome, admin")')-> count() > 0,'mauvais accès en zone admin');
	}

	public function testDeconnecter()
	{
		$client = static::createClient();
		$crawler = $client -> request('GET', '/en/donnees/admin/enregistrer');

		$crawler = $client->followRedirect();

		$form = $crawler -> selectButton('submit') -> form();
		$form['_username'] = 'admin';
		$form['_password'] = 'adminpass';
		$crawler = $client->submit($form);

		$crawler = $client->followRedirect();

		$crawler = $client -> request('GET', '/en/donnees/admin/logout');

		$this->assertTrue($crawler->filter('html:contains("Welcome, admin")')-> count() == 0,'problème déconnection');
	}
}
