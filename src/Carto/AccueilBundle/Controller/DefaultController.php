<?php

namespace Carto\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

	public function indexAction()
	{
		return $this->render('CartoAccueilBundle:Default:index.html.twig');
	}

	public function switchLanguageAction($lang = null)
	{
		if ($lang != null) {
			$this->get('request')->setLocale($lang);
		}
		
		$currentUrl = $this->getRequest()->getUri();
		return $this->redirect(
			$this->generateUrl(
				'carto_accueil_homepage',
				array('_locale'=> $this->get('request')->getLocale())
			)
		);
	}

	public function aboutusAction()
	{
		return $this->render('CartoAccueilBundle:Default:aboutus.html.twig');
	}
	
	public function contactusAction()
	{
		return $this->render('CartoAccueilBundle:Default:contactus.html.twig');
	}

}
