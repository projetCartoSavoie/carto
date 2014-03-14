<?php

namespace Carto\DonneesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction($recherche)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:Mot');

		//Recherche du mot recherché
		$mot = $mrep -> findOneByMot($recherche);

		//Récupération de ses synsets et de leurs mots
		$nsynsets = $mot -> getNsynsets();
		//foreach ($nsynsets as $syn) { $mots = $syn -> getMots(); }
		$vsynsets = $mot -> getVsynsets();
		//foreach ($vsynsets as $syn) { $mots = $syn -> getMots(); }
		$asynsets = $mot -> getAsynsets();
		//foreach ($asynsets as $syn) { $mots = $syn -> getMots(); }
		$rsynsets = $mot -> getRsynsets();
		//foreach ($rsynsets as $syn) { $mots = $syn -> getMots(); }

		//Récupération de ses relations directes entre mots
		$derivede = $mot -> getDeriveFrom();
		$deriveto = $mot -> getDeriveTo();
		$pertainfrom = $mot -> getPertainFrom();
		$pertainto = $mot -> getPertainTo();
		$participleof = $mot -> getParticipleOf();
		$participleto = $mot -> getParticipleTo();
		$builtfrom = $mot -> getBuiltFrom();
		$build = $mot -> getBuild();

		return $this->render('CartoDonneesBundle:Default:index.html.twig',
			array(
				'mot' => $mot,
				'nsynsets' => $nsynsets,
				'vsynsets' => $vsynsets,
				'asynsets' => $asynsets,
				'rsynsets' => $rsynsets,
				'derivede' => $derivede,
				'deriveto' => $deriveto,
				'pertainfrom' => $pertainfrom,
				'pertainto' => $pertainto,
				'participleof' => $participleof,
				'participleto' => $participleto,
				'builtfrom' => $builtfrom,
				'build' => $build
		));
	}
}
