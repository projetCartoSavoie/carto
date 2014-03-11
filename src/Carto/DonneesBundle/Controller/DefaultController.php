<?php

namespace Carto\DonneesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction($recherche)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:Mot');
		$mot = $mrep -> findOneByMot($recherche);
		$nsynsets = $mot -> getNsynsets();
		return $this->render('CartoDonneesBundle:Default:index.html.twig',array('nsynsets' => $nsynsets));
	}
}
