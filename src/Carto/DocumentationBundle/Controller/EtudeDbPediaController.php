<?php
/**
 * Controleur pour la doc DbPedia
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
namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controleur pour la doc DbPedia
 */
class EtudeDbPediaController extends Controller
{

/**
 * Rendu visuel du compte rendu
 *
 * @return vue twig
 */
	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeDbPedia:index.html.twig');
	}

}
