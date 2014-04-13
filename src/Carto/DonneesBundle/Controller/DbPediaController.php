<?php
/**
 * Controleur pour les recherches DbPedia
 *
 *
 * @author Rémy Cluze <Remy.Cluze@etu.univ-savoie.fr>
 * @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
 * @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
 * @author Rémi Mollard <Remi.Mollard@etu.univ-savoie.fr>
 * @author Céline de Roland <Celine.de-Roland@etu.univ-savoie.fr>
 *
 * @version 1.0
 */

namespace Carto\DonneesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Carto\DonneesBundle\Entity\WN\ASynset;
use Carto\DonneesBundle\Entity\WN\RSynset;
use Carto\DonneesBundle\Entity\WN\NSynset;
use Carto\DonneesBundle\Entity\WN\VSynset;
use Carto\DonneesBundle\Entity\WN\Mot;

/**
 * Controleur pour les recherches DbPedia
 */
class DbPediaController extends Controller
{

	/**
	 * fonction index
	 *
	 * Effectue une recherche simple : pour un mot donné, on trouve ses relations directes, ses synsets et les relations directes de ces synsets
	 *
	 * @param string $recherche
	 * @return Vue twig
	*/
	public function indexAction($recherche)
	{
	}

}
