<?php

namespace Carto\RepresentationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
 
use Carto\RepresentationsBundle\Form\Type\JsonSearchPostType;

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

	public function representation4Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation4.html.twig');
	}

	public function representation6Action()
	{
		return $this->render('CartoRepresentationsBundle:Default:representation6.html.twig');
	}
	
	public function jsonRechercher()
	{
		return "{success:true}";
	}
}
