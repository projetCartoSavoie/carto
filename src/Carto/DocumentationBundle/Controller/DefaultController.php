<?php

namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DocumentationBundle:Default:index.html.twig', array('name' => $name));
    }
}
