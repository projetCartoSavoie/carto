<?php
/**
 * Controleur pour le panneau d'administration
 *
 * @author Rémy Cluze <Remy.Cluze@etu.univ-savoie.fr>
 * @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
 * @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
 * @author Rémi Mollard <Remi.Mollard@etu.univ-savoie.fr>
 * @author Céline de Roland <Celine.de-Roland@etu.univ-savoie.fr>
 *
 * @version 1.0
 */
namespace Carto\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controleur pour le panneau d'administration
 */
class DashboardController extends Controller
{

/**
 * Renvoie la vue d'accueil des administrateurs
 *
 * @return vue twig
 */
	public function indexAction()
	{
		return $this->render('CartoUserBundle:Dashboard:index.html.twig');
	}
}
