<?php

namespace Carto\LivreOrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Carto\LivreOrBundle\Entity\Commentaire;

class DefaultController extends Controller
{
	public function indexAction()
	{
		$manager = $this -> getDoctrine() -> getManager();
		$request = $this->get('request');

		$comment = new Commentaire();
		$formBuilder = $this -> createFormBuilder($comment);
		$formBuilder -> add('auteur','text');
		$formBuilder -> add('contenu','textarea');
		$formBuilder -> add('captcha', 'captcha');
		$form = $formBuilder -> getForm();
		if ($request->getMethod() == 'POST') 
		{
			$form -> bind($request);
			if ($form->isValid()) 
			{
				$manager -> persist($comment);
				$manager -> flush();
			}
		}

		$form = $form -> createView();

		$com_rep = $manager -> getRepository('CartoLivreOrBundle:Commentaire');
		$comments = $com_rep -> findAll();
		return $this->render('CartoLivreOrBundle:Default:index.html.twig', array('form' => $form, 'comments' => $comments));
	}
}
