<?php

namespace Carto\DonneesBundle\Tests\Unitaire;

class MotTest extends RepTestCase
{

	public function testAllDerive()
	{
		$mrep = $this -> manager -> getRepository('CartoDonneesBundle:WN\Mot');

		$mot = $mrep -> trouve('pretty');

		$reponse = $mot -> getAllDerive();

		$actual_size = count($reponse);
		$actual_reponse = $reponse[0] -> getMot();

		$expected_size = 1;
		$expected_reponse = 'prettiness';

		$this -> assertEquals($actual_size,$expected_size,'trop ou pas assez de dérivation');
		$this -> assertEquals($actual_reponse,$expected_reponse,'pas les bonnes dérivation');
	}

	public function testAllBuild()
	{
		$mrep = $this -> manager -> getRepository('CartoDonneesBundle:WN\Mot');

		$mot = $mrep -> trouve('pretty');

		$reponse = $mot -> getAllBuild();

		$actual_size = count($reponse);
		$actual_reponse = $reponse[0] -> getMot();

		$expected_size = 1;
		$expected_reponse = 'prettily';

		$this -> assertEquals($actual_size,$expected_size,'trop ou pas assez de construction');
		$this -> assertEquals($actual_reponse,$expected_reponse,'pas les bonnes constructions');
	}

	public function testAllPertainym()
	{
		$mrep = $this -> manager -> getRepository('CartoDonneesBundle:WN\Mot');

		$mot = $mrep -> trouve('drama');

		$reponse = $mot -> getAllPertainym();

		$actual_size = count($reponse);
		$actual_reponse0 = $reponse[0] -> getMot();
		$actual_reponse1 = $reponse[1] -> getMot();

		$expected_size = 2;
		$expected_reponse0 = 'dramatic';
		$expected_reponse1 = 'thespian';

		$this -> assertEquals($actual_size,$expected_size,'trop ou pas assez de construction');
		$this -> assertEquals($actual_reponse0,$expected_reponse0,'pas les bonnes constructions');
		$this -> assertEquals($actual_reponse1,$expected_reponse1,'pas les bonnes constructions');
	}

	public function testAllParticiple()
	{
		$mrep = $this -> manager -> getRepository('CartoDonneesBundle:WN\Mot');

		$mot = $mrep -> trouve('avenge');

		$reponse = $mot -> getAllParticiple();

		$actual_size = count($reponse);
		$actual_reponse = $reponse[0] -> getMot();

		$expected_size = 1;
		$expected_reponse = 'avenged';

		$this -> assertEquals($actual_size,$expected_size,'trop ou pas assez de construction');
		$this -> assertEquals($actual_reponse,$expected_reponse,'pas les bonnes constructions');
	}

	public function testAllSynsets()
	{
		$mrep = $this -> manager -> getRepository('CartoDonneesBundle:WN\Mot');

		$mot = $mrep -> trouve('horse');

		$reponse = $mot -> getAllSynsets();

		$actual_size = count($reponse);

		$expected_size = 6;

		$this -> assertEquals($actual_size,$expected_size,'trop ou pas assez de synsets');
	}
}
