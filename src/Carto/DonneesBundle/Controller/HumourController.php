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
use Carto\DonneesBundle\Entity\Humour\Relation;
use Carto\DonneesBundle\Entity\Humour\Triplet;

/**
 * Controleur pour les recherches WordNet
 */
class HumourController extends Controller
{

	public function objetAction()
	{
		$manager = $this -> getDoctrine() -> getManager();

		$request = $this -> get('request');

		$objet = new Objet();
		$formBuilder = $this -> createFormBuilder($objet);
		$formBuilder -> add('titre','text');
		$formBuilder -> add('type','text');
		$formBuilder -> add('description','textarea');
		$formBuilder -> add('image','text');
		$form = $formBuilder -> getForm();
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
		$triplets = $objet -> getAlltriplets();
		foreach($triplets as $triplet)
		{
			$manager -> remove($triplet);
		}
		$manager -> remove($objet);
		$manager -> flush();
		return $this -> objetAction();
	}

	public function objetEditAction($id)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$obj_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Objet');
		$objet = $obj_rep -> find($id);

		$request = $this -> get('request');

		$formBuilder = $this -> createFormBuilder($objet);
		$formBuilder -> add('titre','text');
		$formBuilder -> add('type','text');
		$formBuilder -> add('description','textarea');
		$formBuilder -> add('image','text');
		$form = $formBuilder -> getForm();
		if ($request -> getMethod() == 'POST') 
		{
			$form -> bind($request);
			if ($form -> isValid()) 
			{
				$manager -> persist($objet);
				$manager -> flush();
			}
			//return $this -> objetAction();
		}

		$form = $form -> createView();
		
		return $this->render('CartoDonneesBundle:Humour:objetEdit.html.twig', array('form' => $form, 'obj' => $objet));
	}

	public function relationAction()
	{
		$manager = $this -> getDoctrine() -> getManager();

		$request = $this -> get('request');

		$relation = new Relation();
		$formBuilder = $this -> createFormBuilder($relation);
		$formBuilder -> add('titre','text');
		$form = $formBuilder -> getForm();
		if ($request -> getMethod() == 'POST') 
		{
			$form -> bind($request);
			if ($form -> isValid()) 
			{
				$manager -> persist($relation);
				$manager -> flush();
			}
		}

		$form = $form -> createView();

		$rel_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Relation');
		$relations = $rel_rep -> findAll();
		
		return $this->render('CartoDonneesBundle:Humour:relation.html.twig', array('form' => $form, 'relations' => $relations));
	}

	public function relationSupprAction($id)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$rel_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Relation');
		$relation = $rel_rep -> find($id);
		$triplets = $relation -> getTriplets();
		foreach($triplets as $triplet)
		{
			$manager -> remove($triplet);
		}
		$manager -> remove($relation);
		$manager -> flush();
		return $this -> relationAction();
	}

	public function tripletAction()
	{
		$manager = $this -> getDoctrine() -> getManager();

		$request = $this -> get('request');

		$triplet = new Triplet();
		$formBuilder = $this -> createFormBuilder($triplet);
		$formBuilder -> add('sujet','entity',array('class' => 'CartoDonneesBundle:Humour\Objet', 'property' => 'titre', 'multiple' => false));
		$formBuilder -> add('relation','entity',array('class' => 'CartoDonneesBundle:Humour\Relation', 'property' => 'titre', 'multiple' => false));
		$formBuilder -> add('objet','entity',array('class' => 'CartoDonneesBundle:Humour\Objet', 'property' => 'titre', 'multiple' => false));
		$form = $formBuilder -> getForm();
		if ($request -> getMethod() == 'POST') 
		{
			$form -> bind($request);
			if ($form -> isValid()) 
			{
				$manager -> persist($triplet);
				$manager -> flush();
			}
		}

		$form = $form -> createView();

		$tri_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Triplet');
		$triplets = $tri_rep -> findAll();
		return $this->render('CartoDonneesBundle:Humour:triplet.html.twig', array('form' => $form,'triplets' => $triplets));
	}

	public function tripletSupprAction($id)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$tri_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Triplet');
		$triplet = $tri_rep -> find($id);
		$manager -> remove($triplet);
		$manager -> flush();
		return $this -> tripletAction();
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
	public function jsonAction($recherche,$relations)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:Humour\Objet');
		$rel_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Relation');
		$mesrels = $rel_rep -> findAll();
		$allrelations = array();
		foreach($mesrels as $relation)
		{
			$allrelations[] = $relation -> getTitre();
		}

		$text = json_encode($mrep -> fabriqueGraphe($recherche,$relations,$allrelations));

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
	public function recupRelationsAction()
	{
		$manager = $this -> getDoctrine() -> getManager();
		$rel_rep = $manager -> getRepository('CartoDonneesBundle:Humour\Relation');
		$relations = $rel_rep -> findAll();
		$tab = array();
		foreach($relations as $relation)
		{
			$tab[] = $relation -> getTitre();
		}
		$text = json_encode($tab);
		return new Response($text);
	}

	public function descriptionAction($mot)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:Humour\Objet');
		$objet = $mrep -> trouve($mot);
		//var_dump($objet);
		return $this->render('CartoDonneesBundle:Humour:objetShow.html.twig', array('obj' => $objet));
		return new Response($objet -> getDescription());
	}
}
