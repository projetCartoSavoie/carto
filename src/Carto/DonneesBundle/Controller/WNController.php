<?php
/**
 * Controleur pour les recherches WordNet
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
 * Controleur pour les recherches WordNet
 */
class WNController extends Controller
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
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:WN\Mot');

		//Recherche du mot recherché
		$this -> mot = $mrep -> trouve($recherche);

		//Récupération de ses synsets et de leurs mots
		$nsynsets = $this -> mot -> getNsynsets();
		$vsynsets = $this -> mot -> getVsynsets();
		$asynsets = $this -> mot -> getAsynsets();
		$rsynsets = $this -> mot -> getRsynsets();

		//Récupération de ses relations directes entre mots
		$derivede = $this -> mot -> getDeriveFrom();
		$deriveto = $this -> mot -> getDeriveTo();
		$pertainfrom = $this -> mot -> getPertainFrom();
		$pertainto = $this -> mot -> getPertainTo();
		$participleof = $this -> mot -> getParticipleOf();
		$participleto = $this -> mot -> getParticipleTo();
		$builtfrom = $this -> mot -> getBuiltFrom();
		$build = $this -> mot -> getBuild();

		return $this->render('CartoDonneesBundle:WN:index.html.twig',
			array(
				'mot' => $this -> mot,
				'nsynsets' => $nsynsets,
				'vsynsets' => $vsynsets,
				'asynsets' => $asynsets,
				'rsynsets' => $rsynsets,
				'derivede' => $derivede,
				'deriveto' => $deriveto,
				'pertainfrom' => $pertainfrom,
				'pertainto' => $pertainto,
				'participleof' => $participleof,
				'participleto' => $participleto,
				'builtfrom' => $builtfrom,
				'build' => $build
		));
	}

	/**
	 * fonction json
	 *
	 * Renvoie un fichier json correspondant au format commun établi.
	 * correspondant à une recherche de profondeur 3 à partir du mot demandé
	 *
	 * @param string $recherche
	 * @return Vue twig
	*/
	public function jsonAction($recherche)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:WN\Mot');

		$text = json_encode($mrep -> fabriqueGraphe($recherche));

		//On retourne le json obtenu
		return new Response($text);
	}
}
