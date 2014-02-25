<?php

namespace Carto\AccueiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AccueilBundle:Default:index.html.twig', array('name' => $name));
    }
}
