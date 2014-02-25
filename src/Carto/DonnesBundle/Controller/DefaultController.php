<?php

namespace Carto\DonnesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('donnesBundle:Default:index.html.twig', array('name' => $name));
    }
}
