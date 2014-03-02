<?php

namespace Carto\RepresentationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CartoRepresentationsBundle:Default:index.html.twig');
    }
}
