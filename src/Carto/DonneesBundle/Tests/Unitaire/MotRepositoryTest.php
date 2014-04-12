<?php

namespace Carto\DonneesBundle\Tests\Unitaire;

class MotRepositoryTest extends RepTestCase
{

	public function testTrouve()
	{
		//-------------------------------------------------------------
		//Initialisation
		//-------------------------------------------------------------

		//Cette ligne remplace $mrep = new MotRepository(), qui ne fonctionne pas en raison du pattern injection de dépendance de symfony
		//Pour ceux que ça intéresse de savoir comment ça marche (pas indispensable pour écrire vos propres tests), dans la classe RepTestCase j'ai fait appel au conteneur de services pour nous donner le service manager, qui lui nous fournit les repository qu'on veut
		$mrep = $this -> manager -> getRepository('CartoDonneesBundle:WN\Mot');

		//-------------------------------------------------------------
		//Test cas numéro 1 : recherche d'un mot présent dans la bdd
		//-------------------------------------------------------------

		//J'appelle la fonction trouve, je note sa réponse
		$reponse = $mrep -> trouve('entity') -> getMot();
		//Elle est sensée répondre 'trouve' car j'ai cherché un mot qui est bien dans la bdd
		$expected = 'entity';

		//Je vérifie que sa réponse correspond bien à la réponse attendue
		$this->assertTrue($reponse == $expected);

		/*
		//-------------------------------------------------------------
		//Test cas numéro 2 : recherche d'un mot non présent dans la bdd
		//-------------------------------------------------------------
		
		//J'appelle la fonction trouve, je note sa réponse
		$reponse = $mrep -> trouve('antity') -> getMot();
		//Elle est sensée répondre 'non trouve' car j'ai cherché un mot qui n'est pas dans la bdd
		$expected = 'antitype';

		//Je vérifie que sa réponse correspond bien à la réponse attendue
		$this->assertTrue($reponse == $expected);
		*/
	}
}
