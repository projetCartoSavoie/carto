<?php
// Ne pas modifier cette classe
namespace Carto\DonneesBundle\Tests\Unitaire;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepTestCase extends WebTestCase
{
	protected $manager;

	public function __construct() 
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$this->manager = $kernel
			->getContainer()
			->get('doctrine.orm.entity_manager')
		;
	}

}
