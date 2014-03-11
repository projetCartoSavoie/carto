<?php

namespace Carto\DonneesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Carto\DonneesBundle\Entity\NSynset;
use Doctrine\Common\Persistence\ObjectManager;

class LoadNSynset1 extends AbstractFixture implements OrderedFixtureInterface
{
	private $manager;

	public function load(ObjectManager $manager)
	{
		$this -> manager = $manager;
		$this -> generateEntities();
		$manager -> flush();
	}

	public function getOrder()
	{
		return 1;
	}

	private function generateEntities()
	{
		$data = fopen("src/Carto/DonneesBundle/Resources/dict/mydata.noun","r");

		$prefixe = 0;
		while ($ligne = fgets($data))
		{
			$tab = explode(' ',$ligne,2);
			$wnid = $tab[0];
			if (intval($wnid) < 2000000)
			{
				echo $wnid.' : ';
				$tab2 = explode('|',$tab[1]);
				$def = $tab2[1];
				echo $def.'
	';
				$syn = new NSynset($wnid,$def);
				$this -> manager -> persist($syn);
				$this -> addReference('nsyn-'.$wnid,$syn);
			}
		}
	}

}
?>
