<?php

namespace Carto\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CartoAccueilBundle:Default:index.html.twig');
    }
}
