<?php

namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EtudeDbPediaController extends Controller
{
	//Rendus visuels du compte rendu

	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeDbPedia:index.html.twig');
	}

}
