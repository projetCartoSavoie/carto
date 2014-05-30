<?php

namespace Carto\DonneesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DBControllerTest extends WebTestCase
{
	public function testJson()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/en/donnees/dbpedia/json/horse/10');

		$reponse = $client -> getResponse() -> getContent();
		$expected = '{"noeuds":[{"id":"0","nom":"Horse"},{"id":"1","nom":"Wild_horse"},{"id":"2","nom":"Thing"},{"id":"3","nom":"Class"},{"id":"5","nom":"Animal"},{"id":"9","nom":"Carl_Linnaeus"},{"id":"13","nom":"Chordate"},{"id":"21","nom":"Equus_(genus)"},{"id":"25","nom":"Odd-toed_ungulate"},{"id":"33","nom":"Equidae"}],"graphe":[{"noeud":"0","species":["1"],"kingdom":["5"],"trinomialAuthority":["9"],"phylum":["13","13"],"genus":["21"],"order":["25"],"ordo":["25"],"family":["33"],"familia":["33"]},{"noeud":"1","type":["2"]},{"noeud":"2","type":["3","3","3","3","3","3","3","3","3","3"]},{"noeud":"3"},{"noeud":"5","type":["2"]},{"noeud":"9","type":["2"]},{"noeud":"13","type":["2","2"]},{"noeud":"21","type":["2"]},{"noeud":"25","type":["2","2"]},{"noeud":"33","type":["2","2"]}],"relations":["species","type","kingdom","trinomialAuthority","phylum","genus","order","ordo","family","familia"]}';

		$expected = str_replace('\n','',$expected);
		$expected = str_replace('  ',' ',$expected);

		$reponse = str_replace('\n','',$reponse);
		$reponse = str_replace('  ',' ',$reponse);

		$this -> assertEquals($expected,$reponse,'génération json incorrecte');
	}

}
