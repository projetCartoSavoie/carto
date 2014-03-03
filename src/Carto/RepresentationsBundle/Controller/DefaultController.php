<?php

namespace Carto\RepresentationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CartoRepresentationsBundle:Default:index.html.twig');
    }
	
	public function representation1Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation1.html.twig');
	}
	
	public function representation2Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation2.html.twig');
	}
	
	public function representation3Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation3.html.twig');
	}
}
