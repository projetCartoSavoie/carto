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
use Carto\DonneesBundle\Entity\Humour\Objet;

/**
 * Controleur pour les recherches WordNet
 */
class HumourController extends Controller
{

/**
 * Création d'un formulaire
 *
 * @param Sujet $sujet
 * @return FormBuilder
 */
	public function formulaire($sujet)
	{
		$formBuilder = $this -> createFormBuilder($sujet);
		$formBuilder -> add('titre','text');
		$formBuilder -> add('description','textarea');
		return $formBuilder;
	}

	public function objetAction()
	{
		$manager = $this -> getDoctrine() -> getManager();

		$request = $this -> get('request');

		$objet = new Objet();
		$form = $this -> formulaire($objet) -> getForm();
		if ($request -> getMethod() == 'POST') 
		{
			$form -> bind($request);
			if ($form -> isValid()) 
			{
				$manager -> persist($objet);
				$manager -> flush();
			}
		}

		$form = $form -> createView();

		$obj_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Objet');
		$objets = $obj_rep -> findAll();
		
		return $this->render('CartoDonneesBundle:Humour:objet.html.twig', array('form' => $form, 'objets' => $objets));
	}

	public function objetSupprAction($id)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$obj_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Objet');
		$objet = $obj_rep -> find($id);
		$manager -> remove($objet);
		$manager -> flush();
		return $this -> objetAction();
	}

	/**
	 * fonction json
	 *
	 * Renvoie un fichier json correspondant au format commun établi.
	 * correspondant à une recherche.
	 *
	 * @param string $recherche : mot demandé
	 * @param string $relations : liste des relations à prendre en compte
	 * @param integer $profondeur : niveau de profondeur demandé
	 * @return Réponse http
	*/
	public function jsonAction($recherche,$relations,$profondeur)
	{
		/*$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:WN\Mot');

		$text = json_encode($mrep -> fabriqueGraphe($recherche,$relations,$profondeur));*/ $text = "";

		//On retourne le json obtenu
		return new Response($text);
	}

	/**
	 * fonction relations
	 *
	 * Renvoie un fichier json indiquant l'ensemble des options.
	 *
	 * @return Réponse http
	*/
	public function relationsAction()
	{
		$tab = array(
		);
		$text = json_encode($tab);
		return new Response($text);
	}
}
