<?php

namespace Carto\DonneesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WNControllerTest extends WebTestCase
{
	public function testJson()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/fr/donnees/json/pretty');

		$reponse = $client -> getResponse() -> getContent();
		$expected = '{"noeuds":[{"id":"M104383","nom":"pretty","type":"M"},{"id":"M104382","nom":"prettiness","type":"M"},{"id":"M104381","nom":"prettily","type":"M"},{"id":"A1211","nom":" pleasing by delicacy or grace; not imposing; \"pretty girl\"; \"pretty song\"; \"pretty room\" \n","type":"A"},{"id":"A6196","nom":" (used ironically) unexpectedly bad; \"a pretty mess\"; \"a pretty kettle of fish\" \n","type":"A"},{"id":"R193","nom":" to certain extent or degree; \"pretty big\"; \"pretty bad\"; \"jolly decent of him\"; \"the shoes are priced reasonably\"; \"he is fairly clever with computers\" \n","type":"R"},{"id":"M45591","nom":"fairly","type":"M"},{"id":"M72572","nom":"jolly","type":"M"},{"id":"M84774","nom":"middling","type":"M"},{"id":"M85868","nom":"moderately","type":"M"},{"id":"M97202","nom":"passably","type":"M"},{"id":"M108934","nom":"reasonably","type":"M"},{"id":"M122741","nom":"somewhat","type":"M"},{"id":"A1201","nom":" delighting the senses or exciting intellectual or emotional admiration; \"a beautiful child\"; \"beautiful country\"; \"a beautiful painting\"; \"a beautiful theory\"; \"a beautiful party\" \n","type":"A"},{"id":"M12549","nom":"beautiful","type":"M"},{"id":"A6182","nom":" having undesirable or negative qualities; \"a bad report card\"; \"his sloppy appearance made a bad impression\"; \"a bad little boy\"; \"clothes in bad shape\"; \"a bad cut\"; \"bad luck\"; \"the news was very bad\"; \"the reviews were bad\"; \"the pay is bad\"; \"it was a bad light for reading\"; \"the movie was a bad choice\" \n","type":"A"},{"id":"M10783","nom":"bad","type":"M"},{"id":"R194","nom":" to a degree that exceeds the bounds or reason or moderation; \"his prices are unreasonably high\" \n","type":"R"},{"id":"M67578","nom":"immoderately","type":"M"},{"id":"M139071","nom":"unreasonably","type":"M"}],"relations":["derive","pertainym","build","participle","hypernym","troponym","hyponym","meronym","entails","holonym","antonym","attribut","cause","consequence","similar","estdans","contient"],"graphe":[{"noeud":"M104383","derive":["M104382"],"build":["M104381"],"estdans":["A1211","A6196","R193"]},{"noeud":"M104382","derive":["M104383"]},{"build":["M104383"]},{"noeud":"A1211","contient":["M104383"],"similar":["A1201"]},{"noeud":"A6196","contient":["M104383"],"similar":["A6182"]},{"noeud":"R193","contient":["M104383","M45591","M72572","M84774","M85868","M97202","M108934","M122741"],"antonym":["R194"]},{"noeud":"M45591","estdans":["R193"]},{"noeud":"M72572","estdans":["R193"]},{"noeud":"M84774","estdans":["R193"]},{"noeud":"M85868","estdans":["R193"]},{"noeud":"M97202","estdans":["R193"]},{"noeud":"M108934","estdans":["R193"]},{"noeud":"M122741","estdans":["R193"]},{"noeud":"A1201","similar":["A1211"],"contient":["M12549"]},{"noeud":"M12549","estdans":["A1201"]},{"noeud":"A6182","similar":["A6196"],"contient":["M10783"]},{"noeud":"M10783","estdans":["A6182"]},{"noeud":"R194","antonym":["R193"],"contient":["M67578","M139071"]},{"noeud":"M67578","estdans":["R194"]},{"noeud":"M139071","estdans":["R194"]}]}';

		$expected = str_replace('\n','',$expected);
		$expected = str_replace('  ',' ',$expected);

		$reponse = str_replace('\n','',$reponse);
		$reponse = str_replace('  ',' ',$reponse);

		$this -> assertEquals($expected,$reponse,'génération json incorrecte');
	}
}
