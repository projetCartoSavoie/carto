<?php
/**
 * Controleur pour la documentation du site
 *
 * @author Rémy Cluze <Remy.Cluze@etu.univ-savoie.fr>
 * @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
 * @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
 * @author Rémi Mollard <Remi.Mollard@etu.univ-savoie.fr>
 * @author Céline de Roland <Celine.de-Roland@etu.univ-savoie.fr>
 *
 * @version 1.0
 */
namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controleur pour la documentation du site
 */
class DefaultController extends Controller
{
/**
 * Accueil
 *
 * @return vue twig
 */
	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:Default:index.html.twig');
	}
}
