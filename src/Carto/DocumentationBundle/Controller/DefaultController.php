<?php

namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:Default:index.html.twig');
	}
}