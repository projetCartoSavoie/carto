<?php

namespace Carto\DonneesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Carto\DonneesBundle\Entity\ASynset;
use Carto\DonneesBundle\Entity\RSynset;
use Carto\DonneesBundle\Entity\NSynset;
use Carto\DonneesBundle\Entity\VSynset;
use Carto\DonneesBundle\Entity\Mot;

class DefaultController extends Controller
{
	private $resultat;

	public function indexAction($recherche)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:Mot');

		//Recherche du mot recherché
		$this -> mot = $mrep -> findOneByMot($recherche);

		//Récupération de ses synsets et de leurs mots
		$nsynsets = $this -> mot -> getNsynsets();
		//foreach ($nsynsets as $syn) { $this -> mots = $syn -> getMots(); }
		$vsynsets = $this -> mot -> getVsynsets();
		//foreach ($vsynsets as $syn) { $this -> mots = $syn -> getMots(); }
		$asynsets = $this -> mot -> getAsynsets();
		//foreach ($asynsets as $syn) { $this -> mots = $syn -> getMots(); }
		$rsynsets = $this -> mot -> getRsynsets();
		//foreach ($rsynsets as $syn) { $this -> mots = $syn -> getMots(); }

		//Récupération de ses relations directes entre mots
		$derivede = $this -> mot -> getDeriveFrom();
		$deriveto = $this -> mot -> getDeriveTo();
		$pertainfrom = $this -> mot -> getPertainFrom();
		$pertainto = $this -> mot -> getPertainTo();
		$participleof = $this -> mot -> getParticipleOf();
		$participleto = $this -> mot -> getParticipleTo();
		$builtfrom = $this -> mot -> getBuiltFrom();
		$build = $this -> mot -> getBuild();

		return $this->render('CartoDonneesBundle:Default:index.html.twig',
			array(
				'mot' => $this -> mot,
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

	public function ajoutResultat($entree,$relation,$type)
	{
		foreach ($entree as $valeur) 
		{ 
			$this -> resultat['graphe'][$type.$this -> mot -> getId()][$relation][] = $type.$valeur -> getId();
			if ($type == 'M') { $nom = $valeur -> getMot(); }
			else { $nom = $valeur -> getDefinition(); }
			$n = array(
				'id' => $type.$valeur -> getId(),
				'nom' => $nom,
				'type' => $type
			);
			if (!in_array($n,$this -> resultat['noeuds'])) { $this -> resultat['noeuds'][] = $n; }
		}
	}

	public function ajoutResultatOne($entree,$relation)
	{
		if ($entree != NULL) 
		{ 
			$this -> resultat['graphe']['M'.$this -> mot -> getId()][$relation][] = 'M'.$entree -> getId(); 
			$n = array(
				'id' => 'M'.$entree -> getId(),
				'nom' => $entree -> getMot(),
				'type' => 'M'
			);
			if (!in_array($n,$this -> resultat['noeuds'])) { $this -> resultat['noeuds'][] = $n; }
		}
	}

	public function jsonAction($recherche)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:Mot');

		$this -> resultat = array(
			'noeuds' => array(), 
			'relations' => array(
				'derive',
				'pertainym',
				'build',
				'participle',
				'hypernym',
				'troponym',
				'hyponym',
				'meronym',
				'entails',
				'holonym',
				'antonym',
				'attribut',
				'cause',
				'similar',
				'estdans'
			),
			'graphe' => array()
		);

		//Recherche du mot recherché
		$this -> mot = $mrep -> findOneByMot($recherche);
		$this -> resultat['noeuds'][] = array(
			'id' => 'M'.$this -> mot -> getId(),
			'nom' => $this -> mot -> getMot(),
			'type' => 'M'
		);
		$this -> resultat['graphe']['M'.$this -> mot -> getId()] = array( 'noeud' => 'M'.$this -> mot -> getId() );

		//Recherche des relations du mot
		$this -> ajoutResultat($this -> mot -> getDeriveFrom(),'derive','M');
		$this -> ajoutResultat($this -> mot -> getDeriveTo(),'derive','M');
		$this -> ajoutResultat($this -> mot -> getPertainFrom(),'pertainym','M');
		$this -> ajoutResultat($this -> mot -> getPertainTo(),'pertainym','M');
		$this -> ajoutResultatOne($this -> mot -> getParticipleOf(),'participle');
		$this -> ajoutResultatOne($this -> mot -> getParticipleTo(),'participle');
		$this -> ajoutResultatOne($this -> mot -> getBuiltFrom(),'build');
		$this -> ajoutResultatOne($this -> mot -> getBuild(),'build');

		//Recherche des synsets du mot
		$this -> ajoutResultat($this -> mot -> getNsynsets(),'estdans','N');
		$this -> ajoutResultat($this -> mot -> getVsynsets(),'estdans','V');
		$this -> ajoutResultat($this -> mot -> getAsynsets(),'estdans','A');
		$this -> ajoutResultat($this -> mot -> getRsynsets(),'estdans','R');

		$this -> resultat['graphe'] = array_values($this -> resultat['graphe']);
		return new Response(json_encode($this -> resultat));
	}
}
