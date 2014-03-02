<?php

namespace Carto\DonneesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CartoDonneesBundle:Default:index.html.twig');
    }
}
