<?php

namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjetController extends Controller
{
	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:Projet:index.html.twig');
	}
}