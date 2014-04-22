<?php

namespace Carto\AccueilBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

	public function testIndex()
	{
		$client = static::createClient();

		//Je visite la première page du site
		$crawler = $client->request('GET', '/');
		//Je vérifie qu'elle contient le mot de bienvenue et tous les menus, qui doivent par défaut être en anglais
		$this->assertTrue($crawler->filter('html:contains("Welcome to our web mapping application")')->count() > 0,'affichage Welcome');
		$this->assertTrue($crawler->filter('html:contains("Home")')->count() > 0,'affichage Home');
		$this->assertTrue($crawler->filter('html:contains("Documentation")')->count() > 0,'affichage Documentation');
		$this->assertTrue($crawler->filter('html:contains("Representations")')->count() > 0,'affichage Representations');
		$this->assertTrue($crawler->filter('html:contains("Datas")')->count() > 0,'affichage Datas');
		$this->assertTrue($crawler->filter('html:contains("About us")')->count() > 0,'affichage About us');
		$this->assertTrue($crawler->filter('html:contains("Contact us")')->count() > 0,'affichage Contact us');
	}

	public function testSwitchLang()
	{
		$client = static::createClient();

		//Je vais visiter le site en français
		$crawler = $client->request('GET', '/fr');
		//Je vérifie que les menus ont bien été traduits
		$this->assertTrue($crawler->filter('html:contains("Bienvenue sur notre application web de cartographie")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Accueil")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Documentation")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Représentations")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Données")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("A propos")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Contacts")')->count() > 0);

		//Je repasse en anglais
		$crawler = $client->request('GET', '/en');
		//Je vérifie que les menus ont bien été traduits
		$this->assertTrue($crawler->filter('html:contains("Welcome to our web mapping application")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Home")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Documentation")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Representations")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Datas")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("About us")')->count() > 0);
		$this->assertTrue($crawler->filter('html:contains("Contact us")')->count() > 0);

		/*
		//Si je demande une autre langue non supportée, il faut que ça me ramène sur anglais
		//Non ça ne peut pas car ça devrait alors reconnaitre toute url comme une demande de langue incorrecte
		*/
		$this -> assertTrue(true);
	}

	public function testAboutUs()
	{
		
		//Pour about us, j'ai deux choix : soit je vais visiter directement la page, soit j'y vais depuis la page 
		//d'acceuil en cliquant sur le lien About us. J'ai pris la deuxième solution car ça permet de vérifier
		//que la navigation dans le site se passe bien.

		$client = static::createClient();

		//Je visite la première page du site
		$crawler = $client->request('GET', '/');

		//Je clique sur le lien contenant le texte 'About us'
		$link = $crawler->selectLink('About us')->link();
		$crawler = $client->click($link);

		//Je vérifie que la page s'affiche
		$this->assertTrue($crawler->filter('html:contains("Home")')->count() > 0,'page about us ne fonctionne pas');

		//Il faudra ajouter des tests pour voir si aboutus s'affiche bien (qui remplaceront le test ci-dessus)
		$this->assertTrue($crawler->filter('html:contains("Christophe Roche")')->count() > 0,'page about us ne fonctionne pas');

		$this -> assertTrue(true);
	}

	public function testContactUs()
	{
		
		$client = static::createClient();

		//Je visite la première page du site
		$crawler = $client->request('GET', '/');

		//Je clique sur le lien contenant le texte 'Contact us'
		$link = $crawler->selectLink('Contact us')->link();
		$crawler = $client->click($link);

		//Je vérifie que la page s'affiche
		$this->assertTrue($crawler->filter('html:contains("Home")')->count() > 0,'page contact us ne fonctionne pas');

		//Il faudra ajouter des tests pour voir si contactus s'affiche bien (qui remplaceront le test ci-dessus)
		
		$this -> assertTrue(true);
	}
}
