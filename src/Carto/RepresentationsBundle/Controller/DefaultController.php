<?php
/**
 * Controleur pour les représentations
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
namespace Carto\RepresentationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
 
use Carto\RepresentationsBundle\Form\Type\JsonSearchPostType;

/**
 * Controleur pour les représentations
 */
class DefaultController extends Controller
{

/**
 * Accueil des représentations
 *
 * @return vue twig
 */
	public function indexAction()
	{
		return $this->render('CartoRepresentationsBundle:Default:index.html.twig');
	}

/**
 * Radial Tree
 *
 * @return vue twig
 */
	public function representation1Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation1.html.twig');
	}
	
/**
 * Collapsible Tree
 *
 * @return vue twig
 */
	public function representation2Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation2.html.twig');
	}
	
/**
 * Force directed graphe
 *
 * @return vue twig
 */
	public function representation3Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation3.html.twig');
	}

/**
 * Bubble Tree
 *
 * @return vue twig
 */
	public function representation4Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation4.html.twig');
	}

/**
 * Dependency Wheel
 *
 * @return vue twig
 */
	public function representation6Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation6.html.twig');
	}
	
}
