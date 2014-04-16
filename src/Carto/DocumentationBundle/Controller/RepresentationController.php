<?php
/**
	* Controleur pour la documentation du projet
	*
	*
	* @author R�my Cluze <Remy.Cluze@etu.univ-savoie.fr>
	* @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
	* @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
	* @author R�mi Mollard <Remi.Mollard@etu.univ-savoie.fr>
	* @author C�line de Roland <Celine.de-Roland@etu.univ-savoie.fr>
	*
	* @version 1.0
	*/

namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
	* Controleur pour la documentation de repr�sentation
	*/
class RepresentationController extends Controller
{

/**
	* Renvoie la vue montrant la documentation
	*/
	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:Representation:index.html.twig');
	}
}