<?php

namespace Carto\DonneesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WNControllerTest extends WebTestCase
{
	public function testJson()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/fr/donnees/json/synapse');

		$reponse = $client -> getResponse() -> getContent();
		$expected = '{"noeuds":[{"id":"M129380","nom":"synapse","type":"M"},{"id":"M129385","nom":"synaptic","type":"M"},{"id":"N30198","nom":" the junction between two neurons (axon-to-dendrite) or between a neuron and a muscle; \"nerve impulses cross a synapse through the action of neurotransmitters\" \n","type":"N"},{"id":"M27224","nom":"colligation","type":"M"},{"id":"M28955","nom":"conjugation","type":"M"},{"id":"M28957","nom":"conjunction","type":"M"},{"id":"M72930","nom":"junction","type":"M"},{"id":"N77314","nom":" the state of being joined together \n","type":"N"},{"id":"M138357","nom":"unification","type":"M"},{"id":"M138459","nom":"union","type":"M"},{"id":"N77302","nom":" the state of being joined or united or linked; \"there is strength in union\" \n","type":"N"},{"id":"M125401","nom":"state","type":"M"},{"id":"N34","nom":" the way something is with respect to its main attributes; \"the current state of knowledge\"; \"his state of health\"; \"in a weak financial state\" \n","type":"N"},{"id":"M9626","nom":"attribute","type":"M"},{"id":"N33","nom":" an abstraction belonging to or characteristic of an entity \n","type":"N"},{"id":"M88236","nom":"myoneural_junction","type":"M"},{"id":"M89741","nom":"neuromuscular_junction","type":"M"},{"id":"N30199","nom":" the junction between a nerve fiber and the muscle it supplies \n","type":"N"},{"id":"M89552","nom":"nerve","type":"M"},{"id":"M89595","nom":"nervus","type":"M"},{"id":"N30200","nom":" any bundle of nerve fibers running to various organs and tissues of the body \n","type":"N"}],"relations":["derivation","pertainymie","construction","participe_passe","hypernymie","hyponymie","meronymie","holonymie","troponymie","verbe_hyponymie","entailments","antonymie","attribut","cause","consequence","similar","synonymie","groupe_initial"],"graphe":[{"noeud":"M129380","derivation":["M129385"],"synonymie":["N30198"]},{"noeud":"M129385","pertainymie":["M129380"]},{"noeud":"N30198","hypernymie":["N77314"],"hyponymie":["N30199"],"holonymie":["N30200"]},{"hypernymie":["M27224","M28955","M28957","M72930","N77302"],"noeud":"N77314"},{"noeud":"M27224"},{"noeud":"M28955"},{"noeud":"M28957"},{"noeud":"M72930"},{"hypernymie":["M138357","M138459","N34"],"noeud":"N77302"},{"noeud":"M138357"},{"noeud":"M138459"},{"hypernymie":["M125401","N33"],"noeud":"N34"},{"noeud":"M125401"},{"hypernymie":["M9626"],"noeud":"N33"},{"noeud":"M9626"},{"hyponymie":["M88236","M89741"],"noeud":"N30199"},{"noeud":"M88236"},{"noeud":"M89741"},{"holonymie":["M89552","M89595"],"noeud":"N30200"},{"noeud":"M89552"},{"noeud":"M89595"}]}';

		$expected = str_replace('\n','',$expected);
		$expected = str_replace('  ',' ',$expected);

		$reponse = str_replace('\n','',$reponse);
		$reponse = str_replace('  ',' ',$reponse);

		$this -> assertEquals($expected,$reponse,'génération json incorrecte');
	}

	public function testJsonProfondeur()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/fr/donnees/json/synapse/all/1');

		$reponse = $client -> getResponse() -> getContent();
		$expected = '{"noeuds":[{"id":"M129380","nom":"synapse","type":"M"},{"id":"M129385","nom":"synaptic","type":"M"},{"id":"N30198","nom":" the junction between two neurons (axon-to-dendrite) or between a neuron and a muscle; \"nerve impulses cross a synapse through the action of neurotransmitters\" \n","type":"N"},{"id":"M27224","nom":"colligation","type":"M"},{"id":"M28955","nom":"conjugation","type":"M"},{"id":"M28957","nom":"conjunction","type":"M"},{"id":"M72930","nom":"junction","type":"M"},{"id":"N77314","nom":" the state of being joined together \n","type":"N"},{"id":"M138357","nom":"unification","type":"M"},{"id":"M138459","nom":"union","type":"M"},{"id":"N77302","nom":" the state of being joined or united or linked; \"there is strength in union\" \n","type":"N"},{"id":"M88236","nom":"myoneural_junction","type":"M"},{"id":"M89741","nom":"neuromuscular_junction","type":"M"},{"id":"N30199","nom":" the junction between a nerve fiber and the muscle it supplies \n","type":"N"},{"id":"M89552","nom":"nerve","type":"M"},{"id":"M89595","nom":"nervus","type":"M"},{"id":"N30200","nom":" any bundle of nerve fibers running to various organs and tissues of the body \n","type":"N"}],"relations":["derivation","pertainymie","construction","participe_passe","hypernymie","hyponymie","meronymie","holonymie","troponymie","verbe_hyponymie","entailments","antonymie","attribut","cause","consequence","similar","synonymie","groupe_initial"],"graphe":[{"noeud":"M129380","derivation":["M129385"],"synonymie":["N30198"]},{"noeud":"M129385","pertainymie":["M129380"]},{"noeud":"N30198","hypernymie":["N77314"],"hyponymie":["N30199"],"holonymie":["N30200"]},{"hypernymie":["M27224","M28955","M28957","M72930","N77302"],"noeud":"N77314"},{"noeud":"M27224"},{"noeud":"M28955"},{"noeud":"M28957"},{"noeud":"M72930"},{"hypernymie":["M138357","M138459"],"noeud":"N77302"},{"noeud":"M138357"},{"noeud":"M138459"},{"hyponymie":["M88236","M89741"],"noeud":"N30199"},{"noeud":"M88236"},{"noeud":"M89741"},{"holonymie":["M89552","M89595"],"noeud":"N30200"},{"noeud":"M89552"},{"noeud":"M89595"}]}';

		$expected = str_replace('\n','',$expected);
		$expected = str_replace('  ',' ',$expected);

		$reponse = str_replace('\n','',$reponse);
		$reponse = str_replace('  ',' ',$reponse);

		$this -> assertEquals($expected,$reponse,'génération json incorrecte (profondeur)');
	}

	public function testJsonSelectionRelations()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/fr/donnees/json/synapse/hypernym/3');

		$reponse = $client -> getResponse() -> getContent();
		$expected = '{"noeuds":[{"id":"M129380","nom":"synapse","type":"M"},{"id":"N30198","nom":" the junction between two neurons (axon-to-dendrite) or between a neuron and a muscle; \"nerve impulses cross a synapse through the action of neurotransmitters\" \n","type":"N"}],"relations":["hypernym","groupe_initial"],"graphe":[{"noeud":"M129380","groupe_initial":["N30198"]},{"noeud":"N30198"}]}';

		$expected = str_replace('\n','',$expected);
		$expected = str_replace('  ',' ',$expected);

		$reponse = str_replace('\n','',$reponse);
		$reponse = str_replace('  ',' ',$reponse);

		$this -> assertEquals($expected,$reponse,'génération json incorrecte (relations)');
	}
}
