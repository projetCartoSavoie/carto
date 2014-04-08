<?php

namespace Carto\DonneesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Carto\DonneesBundle\Entity\WN\ASynset;
use Carto\DonneesBundle\Entity\WN\RSynset;
use Carto\DonneesBundle\Entity\WN\NSynset;
use Carto\DonneesBundle\Entity\WN\VSynset;
use Carto\DonneesBundle\Entity\WN\Mot;

class WNController extends Controller
{
	private $resultat;

	public function indexAction($recherche)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:WN\Mot');

		//Recherche du mot recherché
		$this -> mot = $mrep -> findOneByMot($recherche);

		//Récupération de ses synsets et de leurs mots
		$nsynsets = $this -> mot -> getNsynsets();
		$vsynsets = $this -> mot -> getVsynsets();
		$asynsets = $this -> mot -> getAsynsets();
		$rsynsets = $this -> mot -> getRsynsets();

		//Récupération de ses relations directes entre mots
		$derivede = $this -> mot -> getDeriveFrom();
		$deriveto = $this -> mot -> getDeriveTo();
		$pertainfrom = $this -> mot -> getPertainFrom();
		$pertainto = $this -> mot -> getPertainTo();
		$participleof = $this -> mot -> getParticipleOf();
		$participleto = $this -> mot -> getParticipleTo();
		$builtfrom = $this -> mot -> getBuiltFrom();
		$build = $this -> mot -> getBuild();

		return $this->render('CartoDonneesBundle:WN:index.html.twig',
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

	/**
	 * Ajoute un tableau de résultats en relation avec une source
	 * entree : les résultats
	 * relation : la relation entre la source et les résultats
	 * type : le type des entrées
	 * srctype : le type de la source
	 * src : l'id de la source
	**/
	public function ajoutResultat($entree,$relation,$type,$srctype,$src)
	{
		foreach ($entree as $valeur) 
		{ 
			//Préparation du noeud : 'id':'...','nom':'...','type':'...'
			if ($type == 'M') { $nom = $valeur -> getMot(); }
			else { $nom = $valeur -> getDefinition(); }
			$n = array(
				'id' => $type.$valeur -> getId(),
				'nom' => $nom,
				'type' => $type
			);
			//Ajout du noeud dans la liste si il n'y est pas déjà
			if (!in_array($n,$this -> resultat['noeuds'])) { $this -> resultat['noeuds'][] = $n; }
			if (!isset($this -> resultat['graphe'][$srctype.$src])) 
			{
				$this -> resultat['graphe'][$srctype.$src] = array( 'noeud' => $srctype.$src );
			}
			//Ajout de la relation
			$this -> resultat['graphe'][$srctype.$src][$relation][] = $type.$valeur -> getId();
		}
	}

	/**
	 * Ajoute un résultat unique en relation avec une source (pour les relations OneToOne)
	 * entree : le résultat
	 * relation : la relation entre la source et le résultat
	 * inutile de spécifier les types : les seules relations OneToOne qui existent sont entre deux mots
	**/
	public function ajoutResultatOne($entree,$relation)
	{
		if ($entree != NULL) 
		{ 
			//Préparation du noeud : 'id':'...','nom':'...','type':'...'
			$n = array(
				'id' => 'M'.$entree -> getId(),
				'nom' => $entree -> getMot(),
				'type' => 'M'
			);
			//Ajout du noeud dans la liste si il n'y est pas déjà
			if (!in_array($n,$this -> resultat['noeuds'])) { $this -> resultat['noeuds'][] = $n; }
			//Ajout de la relation
			$this -> resultat['graphe']['M'.$this -> mot -> getId()][$relation][] = 'M'.$entree -> getId(); 
		}
	}

	/**
	 * Ajoute tous les mots d'un synset donné
	 * entree : la liste des mots du synset
	 * type : le type de synset
	**/
	public function ajoutMots($entree,$type)
	{
		foreach($entree as $valeur)
		{
			$mots = $valeur -> getMots();
			foreach ($mots as $mot)
			{
				//Ajout du mot dans la liste de noeuds et dans le graphe si il n'y est pas déjà
				if (!isset($this -> resultat['graphe']['M'.$mot -> getId()])) 
				{
					$this -> resultat['noeuds'][] = array(
						'id' => 'M'.$mot -> getId(),
						'nom' => $mot -> getMot(),
						'type' => 'M'
					);
					$this -> resultat['graphe']['M'.$mot -> getId()] = array( 'noeud' => 'M'.$mot -> getId() );
				}
				//Création de la relation 'est dans' pour ce mot si elle n'existe pas déjà
				if (!isset($this -> resultat['graphe']['M'.$mot -> getId()]['estdans']))
				{ 
					$this -> resultat['graphe']['M'.$mot -> getId()]['estdans'] = array();
				}
				//Ajout de la relation si elle n'y est pas déjà
				if (!in_array($type.$valeur -> getId(),$this -> resultat['graphe']['M'.$mot -> getId()]['estdans']))
				{
					$this -> resultat['graphe']['M'.$mot -> getId()]['estdans'][] = $type.$valeur -> getId();
				}
			}
		}
	}

	public function jsonAction($recherche)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$mrep = $manager -> getRepository('CartoDonneesBundle:WN\Mot');

		//Initialisation du tableau résultat, qui sera ensuite encodé en json
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
				'consequence',
				'similar',
				'estdans'
			),
			'graphe' => array()
		);

		//Recherche du mot recherché et ajout dans la liste des noeuds et dans le graphe
		$this -> mot = $mrep -> findOneByMot($recherche);
		$this -> resultat['noeuds'][] = array(
			'id' => 'M'.$this -> mot -> getId(),
			'nom' => $this -> mot -> getMot(),
			'type' => 'M'
		);
		$this -> resultat['graphe']['M'.$this -> mot -> getId()] = array( 'noeud' => 'M'.$this -> mot -> getId() );

		//Recherche des relations du mot
		$this -> ajoutResultat($this -> mot -> getDeriveFrom(),'derive','M','M',$this -> mot -> getId());
		$this -> ajoutResultat($this -> mot -> getDeriveTo(),'derive','M','M',$this -> mot -> getId());
		$this -> ajoutResultat($this -> mot -> getPertainFrom(),'pertainym','M','M',$this -> mot -> getId());
		$this -> ajoutResultat($this -> mot -> getPertainTo(),'pertainym','M','M',$this -> mot -> getId());
		$this -> ajoutResultatOne($this -> mot -> getParticipleOf(),'participle');
		$this -> ajoutResultatOne($this -> mot -> getParticipleTo(),'participle');
		$this -> ajoutResultatOne($this -> mot -> getBuiltFrom(),'build');
		$this -> ajoutResultatOne($this -> mot -> getBuild(),'build');

		//Recherche des synsets du mot
		$this -> ajoutResultat($this -> mot -> getNsynsets(),'estdans','N','M',$this -> mot -> getId());
		$this -> ajoutResultat($this -> mot -> getVsynsets(),'estdans','V','M',$this -> mot -> getId());
		$this -> ajoutResultat($this -> mot -> getAsynsets(),'estdans','A','M',$this -> mot -> getId());
		$this -> ajoutResultat($this -> mot -> getRsynsets(),'estdans','R','M',$this -> mot -> getId());

		//Recherche des mots des synsets
		$this -> ajoutMots($this -> mot -> getNSynsets(),'N');
		$this -> ajoutMots($this -> mot -> getVSynsets(),'V');
		$this -> ajoutMots($this -> mot -> getASynsets(),'A');
		$this -> ajoutMots($this -> mot -> getRSynsets(),'R');

		//Recherche des relations des synsets et de leurs mots
		foreach ($this -> mot -> getNSynsets() as $valeur)
		{
			$this -> ajoutResultat($valeur -> getHypernyms(),'hypernym','N','N',$valeur -> getId());
			$this -> ajoutMots($valeur -> getHypernyms(),'N');
			$this -> ajoutResultat($valeur -> getHyponyms(),'hyponym','N','N',$valeur -> getId());
			$this -> ajoutMots($valeur -> getHyponyms(),'N');
			$this -> ajoutResultat($valeur -> getMeronyms(),'meronym','N','N',$valeur -> getId());
			$this -> ajoutMots($valeur -> getMeronyms(),'N');
			$this -> ajoutResultat($valeur -> getHolonyms(),'holonym','N','N',$valeur -> getId());
			$this -> ajoutMots($valeur -> getHolonyms(),'N');
			$this -> ajoutResultat($valeur -> getAntonyms(),'antonym','N','N',$valeur -> getId());
			$this -> ajoutMots($valeur -> getAntonyms(),'N');
			$this -> ajoutResultat($valeur -> getHasAttribute(),'attribut','A','N',$valeur -> getId());
			$this -> ajoutMots($valeur -> getHasAttribute(),'N');
		}
		foreach ($this -> mot -> getVSynsets() as $valeur)
		{
			$this -> ajoutResultat($valeur -> getTroponyms(),'troponym','V','V',$valeur -> getId());
			$this -> ajoutMots($valeur -> getTroponyms(),'V');
			$this -> ajoutResultat($valeur -> getHyponyms(),'hyponym','V','V',$valeur -> getId());
			$this -> ajoutMots($valeur -> getHyponyms(),'V');
			$this -> ajoutResultat($valeur -> getEntails(),'entails','V','V',$valeur -> getId());
			$this -> ajoutMots($valeur -> getEntails(),'V');
			$this -> ajoutResultat($valeur -> getHolonyms(),'holonym','V','V',$valeur -> getId());
			$this -> ajoutMots($valeur -> getHolonyms(),'V');
			$this -> ajoutResultat($valeur -> getAntonyms(),'antonym','V','V',$valeur -> getId());
			$this -> ajoutMots($valeur -> getAntonyms(),'V');
			$this -> ajoutResultat($valeur -> getCauses(),'cause','V','V',$valeur -> getId());
			$this -> ajoutMots($valeur -> getCauses(),'V');
			$this -> ajoutResultat($valeur -> getConsequences(),'consequence','V','V',$valeur -> getId());
			$this -> ajoutMots($valeur -> getConsequences(),'V');
		}
		foreach ($this -> mot -> getASynsets() as $valeur)
		{
			$this -> ajoutResultat($valeur -> getAntonyms(),'antonym','A','A',$valeur -> getId());
			$this -> ajoutMots($valeur -> getAntonyms(),'A');
			$this -> ajoutResultat($valeur -> getIsAttributeOf(),'attribut','N','A',$valeur -> getId());
			$this -> ajoutMots($valeur -> getIsAttributeOf(),'A');
			$this -> ajoutResultat($valeur -> getSimilars(),'similar','A','A',$valeur -> getId());
			$this -> ajoutMots($valeur -> getSimilars(),'A');
		}
		foreach ($this -> mot -> getRSynsets() as $valeur)
		{
			$this -> ajoutResultat($valeur -> getAntonyms(),'antonym','R','R',$valeur -> getId());
			$this -> ajoutMots($valeur -> getAntonyms(),'R');
		}

		//On enlève les clés du tableau graphe pour correspondre au format commun
		$this -> resultat['graphe'] = array_values($this -> resultat['graphe']);

		//On encode en json et on ajoute quelques sauts de ligne pour faciliter la lecture
		$text = json_encode($this -> resultat);
		/*$text = str_replace('{','
{',$text);
		$text = str_replace('},','},
',$text);
		$text = str_replace('}','}
',$text);
		$text = str_replace('"relations":["derive","pertainym","build","participle","hypernym","troponym","hyponym","meronym","entails","holonym","antonym","attribut","cause","consequence","similar","estdans"],','

"relations":
["derive","pertainym","build","participle","hypernym","troponym","hyponym",
"meronym","entails","holonym","antonym","attribut","cause","consequence","similar","estdans"],

',$text);
		$text = '<html><body><pre>'.$text.'</pre></body></html>';*/

		//On retourne le json obtenu
		return new Response($text);
	}
}