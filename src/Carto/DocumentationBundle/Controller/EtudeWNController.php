<?php

namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EtudeWNController extends Controller
{
	//Rendus visuels du compte rendu

	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:index.html.twig');
	}

	public function wordsAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:words.html.twig');
	}

	public function nsynsetsAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:nsynsets.html.twig');
	}

	public function vsynsetsAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:vsynsets.html.twig');
	}

	public function asynsetsAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:asynsets.html.twig');
	}

	public function rsynsetsAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:rsynsets.html.twig');
	}

	public function conclusionAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:conclusion.html.twig');
	}

	//Traitement des fichiers d'index

	//Renvoie un tableau contenant toutes les lignes simplifiées d'un fichier index.suffix donné
	public function simplifyindex($suffixe,$lettre)
	{
		$index = fopen('../src/Carto/DocumentationBundle/Resources/dict/index.'. $suffixe,'r');
		$reponse = array();

		//On lit chaque ligne du fichier
		while ($ligne = fgets($index))
		{
			//On découpe la ligne
			$tab = explode(' ',$ligne);
			$nouvligne = '';
			//On ne garde que le mot, la lettre indiquant le type et les numéros de synsets
			foreach($tab as $cle => $elem)
			{
				if ($elem == $lettre or $cle == 0 or strlen($elem) > 2) { $nouvligne .= $elem . ' '; }
			}
			$reponse[] = $nouvligne;
		}

		fclose($index);
		return $reponse;
	}

	//Met ensemble les 4 fichiers d'index simplifiés
	public function mixindexAction()
	{
		//On met bout à bout les lignes de tous les fichiers index.suffixe
		$lignes = array();
		$lignes = array_merge($lignes,$this -> simplifyindex('adj','a'));
		$lignes = array_merge($lignes,$this -> simplifyindex('noun','n'));
		$lignes = array_merge($lignes,$this -> simplifyindex('adv','r'));
		$lignes = array_merge($lignes,$this -> simplifyindex('verb','v'));

		//On trie le tableau obtenu par ordre alphabétique
		sort($lignes);

		//On dresse la liste de tous les mots référencés
		$mot = array();
		foreach($lignes as $cle => $l)
		{
			$tab = explode(' ',$l,2);
			$mot[] = $tab[0];
		}

		//On parcourt les lignes, en mettant ensemble les lignes référençant un même mot
		$reponse = '';
		foreach($lignes as $cle => $l)
		{
			//On coupe la ligne en deux : le mot puis la suite contenant la lettre et les synsets
			$tab = explode(' ',$l,2);
			//Si le mot est le même qu'à la ligne précédente, on se contente d'ajouter la suite au bout
			if ($cle != 0 and $mot[$cle - 1] == $mot[$cle]) { $reponse .= $tab[1] . '&nbsp;'; }
			//Sinon on met toute la ligne en dessous de la précédente
			else { $reponse .= $l . '&nbsp;'; }
			//On doit prévoir de sauter une ligne ou non selon que la prochaine ligne référence un nouveau mot ou le même mot
			if (isset($mot[$cle + 1]) and $mot[$cle + 1] != $mot[$cle]) { $reponse .= '<br/>' ; }
		}

		//On renvoie le fichier simplifié et mixé
		return new Response($reponse);
	}

	//Cherche les relations de dérivation
	public function ajoutderivationAction()
	{
		$correspondances = array();

		$index = fopen('../src/Carto/DocumentationBundle/Resources/dict/index.words','r');
		$ndata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.noun','r');
		$vdata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.verb','r');
		$adata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adj','r');

		//On enregistre dans un tableau indexé par les numéros de synset toutes les lignes de data.noun, afin de ne pas avoir ensuite à parcourir tout le fichier pour trouver une ligne.
		while ($nligne = fgets($ndata)) 
		{ 
			$tab = explode(' ',$nligne,2);
			$lignesndata[$tab[0]] = $nligne;
		}

		//On parcourt le fichier data.adj à la recherche du symbole + (qui signifie derivationaly related to)
		while ($ligne = fgets($adata))
		{
			$tab = explode(' ',$ligne);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '+')
				{
					//On note le numéro du synset et des mots concernés
					$nsynset = $tab[$cle + 1];
					$numAdj = intval(substr($tab[$cle + 3],0,2));
					$numNom = intval(substr($tab[$cle + 3],2,2));
					//On va chercher les mots concernés
					if (isset($lignesndata[$nsynset]))
					{
						$ntab = explode(' ', $lignesndata[$nsynset]);
						$nom = $ntab[2*($numNom + 1)];
						$adj = $tab[2*($numAdj + 1)];
						if ($adj != $nom) { $correspondances[] = $adj. ',' . $nom; }
					}
				}
			}
		}

		//On fait exactement la même chose pour les verbes
		while ($ligne = fgets($vdata))
		{
			$tab = explode(' ',$ligne);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '+')
				{
					$nsynset = $tab[$cle + 1];
					$numVerb = intval(substr($tab[$cle + 3],0,2));
					$numNom = intval(substr($tab[$cle + 3],2,2));
					if (isset($lignesndata[$nsynset]))
					{
						$ntab = explode(' ', $lignesndata[$nsynset]);
						$nom = $ntab[2*($numNom + 1)];
						$verb = $tab[2*($numVerb + 1)];
						if ($verb != $nom) { $correspondances[] = $verb. ',' . $nom; }
					}
				}
			}
		}

		fclose($index);
		fclose($ndata);
		fclose($vdata);
		fclose($adata);

		//On les remet en ordre alphabétique et on enlève les doublons (chaque adjectif ou verbe apparait autant de fois qu'il a de synsets)
		$correspondances = array_unique($correspondances);
		sort($correspondances);

		//On affiche le résultat
		$reponse = implode('&nbsp;<br/>',$correspondances);
		return new Response($reponse);
	}

	//Cherche les relations participle
	public function ajoutparticipleAction()
	{
		$correspondances = array();

		$index = fopen('../src/Carto/DocumentationBundle/Resources/dict/index.words','r');
		$vdata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.verb','r');
		$adata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adj','r');

		//On enregistre dans un tableau indexé par les numéros de synset toutes les lignes de data.verb, afin de ne pas avoir ensuite à parcourir tout le fichier pour trouver une ligne.
		while ($vligne = fgets($vdata)) 
		{
			$tab = explode(' ',$vligne,2);
			$lignesvdata[$tab[0]] = $vligne;
		}

		//On parcourt le fichier data.adj à la recherche du symbole < (qui signifie participle of)
		while ($ligne = fgets($adata))
		{
			$tab = explode(' ',$ligne);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '<')
				{
					//On note le numéro du synset et des mots concernés
					$vsynset = $tab[$cle + 1];
					$numAdj = intval(substr($tab[$cle + 3],0,2));
					$numVerb = intval(substr($tab[$cle + 3],2,2));
					//On va chercher les mots concernés
					if (isset($lignesvdata[$vsynset]))
					{
						$vtab = explode(' ', $lignesvdata[$vsynset]);
						$verb = $vtab[2*($numVerb + 1)];
						$adj = $tab[2*($numAdj + 1)];
						if ($adj != $verb) { $correspondances[] = $adj. ',' . $verb; }
					}
				}
			}
		}

		fclose($index);
		fclose($vdata);
		fclose($adata);

		//On les remet en ordre alphabétique et on enlève les doublons (chaque adjectif apparait autant de fois qu'il a de synsets)
		$correspondances = array_unique($correspondances);
		sort($correspondances);

		//On affiche le résultat
		$reponse = implode('&nbsp;<br/>',$correspondances);
		return new Response($reponse);
	}

	//Cherche les relations pertainym
	public function ajoutpertainymAction()
	{
		$correspondances = array();

		$index = fopen('../src/Carto/DocumentationBundle/Resources/dict/index.words','r');
		$ndata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.noun','r');
		$adata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adj','r');

		//On enregistre dans un tableau indexé par les numéros de synset toutes les lignes de data.verb, afin de ne pas avoir ensuite à parcourir tout le fichier pour trouver une ligne.
		while ($nligne = fgets($ndata)) 
		{
			$tab = explode(' ',$nligne,2);
			$lignesndata[$tab[0]] = $nligne;
		}

		//On parcourt le fichier data.adj à la recherche du symbole \ (qui signifie pertainym)
		while ($ligne = fgets($adata))
		{
			$tab = explode(' ',$ligne);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '\\')
				{
					//On note le numéro du synset et des mots concernés
					$nsynset = $tab[$cle + 1];
					$numAdj = intval(substr($tab[$cle + 3],0,2));
					$numNom = intval(substr($tab[$cle + 3],2,2));
					//On va chercher les mots concernés
					if (isset($lignesndata[$nsynset]))
					{
						$ntab = explode(' ', $lignesndata[$nsynset]);
						$nom = $ntab[2*($numNom + 1)];
						$adj = $tab[2*($numAdj + 1)];
						if ($adj != $nom) { $correspondances[] = $adj. ',' . $nom; }
					}
				}
			}
		}

		fclose($index);
		fclose($ndata);
		fclose($adata);

		//On les remet en ordre alphabétique et on enlève les doublons (chaque adjectif apparait autant de fois qu'il a de synsets)
		$correspondances = array_unique($correspondances);
		sort($correspondances);

		//On affiche le résultat
		$reponse = implode('&nbsp;<br/>',$correspondances);
		return new Response($reponse);
	}

	//Cherche les relations builtfrom
	public function ajoutbuiltAction()
	{
		$reponse = '';
		$correspondances = array();

		$index = fopen('../src/Carto/DocumentationBundle/Resources/dict/index.words','r');
		$rdata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adv','r');
		$adata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adj','r');

		//On enregistre dans un tableau indexé par les numéros de synset toutes les lignes de data.adj, afin de ne pas avoir ensuite à parcourir tout le fichier pour trouver une ligne.
		while ($aligne = fgets($adata)) 
		{
			$tab = explode(' ',$aligne,2);
			$lignesadata[$tab[0]] = $aligne;
		}

		//On parcourt le fichier data.adj à la recherche du symbole \ (qui signifie builtfrom)
		while ($ligne = fgets($rdata))
		{
			$tab = explode(' ',$ligne);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '\\')
				{
					//On note le numéro du synset et des mots concernés
					$asynset = $tab[$cle + 1];
					$numAdv = intval(substr($tab[$cle + 3],0,2));
					$numAdj = intval(substr($tab[$cle + 3],2,2));
					//On va chercher les mots concernés
					if (isset($lignesadata[$asynset]))
					{
						$atab = explode(' ', $lignesadata[$asynset]);
						$adj = $atab[2*($numAdj + 1)];
						$adv = $tab[2*($numAdv + 1)];
						if ($adv != $adj) { $correspondances[] = $adv. ',' . $adj; }
					}
					else { 					$reponse .= "je n'ai pas trouvé " . $asynset . '<br/>'; }
				}
			}
		}

		fclose($index);
		fclose($rdata);
		fclose($adata);

		//On les remet en ordre alphabétique et on enlève les doublons (chaque adjectif apparait autant de fois qu'il a de synsets)
		$correspondances = array_unique($correspondances);
		sort($correspondances);

		//On affiche le résultat
		$reponse .= implode('&nbsp;<br/>',$correspondances);
		return new Response($reponse);
	}

	//Traitement des fichiers data
	
	//data.noun
	public function simplifierdatanounAction()
	{
		$reponse = '';
		$data = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.noun','r');
		$symbols = array('@','%p','%m','%s','!','=');
		
		//On parcourt le fichier data.noun ligne par ligne
		while ($ligne = fgets($data))
		{
			//On met d'abord la définition de côté
			$tab = explode('|',$ligne);
			$def = ' | ' . $tab[1];
			//Puis on sépare chaque élément et lorsqu'on rencontre un symbole qu'on souhaite traiter on l'ajoute
			$tab = explode(' ',$tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($cle == 0) { $nouvligne = $valeur . ' '; }
				else if (in_array($valeur,$symbols)) { $nouvligne .= substr($valeur,0,1) . ' ' . $tab[$cle + 1]. ' '; }
			}
			$nouvligne .= $def . '&nbsp;<br/>';
			$reponse .= $nouvligne;
		}
	
		fclose($data);
		return new Response($reponse);
	}

	//data.verb
	public function simplifierdataverbAction()
	{
		$reponse = '';
		$data = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.verb','r');
		$symbols = array('@','!','*','>');
		
		//On parcourt le fichier data.verb ligne par ligne
		while ($ligne = fgets($data))
		{
			//On met d'abord la définition de côté
			$tab = explode('|',$ligne);
			$def = ' | ' . $tab[1];
			//Puis on sépare chaque élément et lorsqu'on rencontre un symbole qu'on souhaite traiter on l'ajoute
			$tab = explode(' ',$tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($cle == 0) { $nouvligne = $valeur . ' '; }
				else if (in_array($valeur,$symbols)) { $nouvligne .= substr($valeur,0,1) . ' ' . $tab[$cle + 1]. ' '; }
			}
			$nouvligne .= $def . '&nbsp;<br/>';
			$reponse .= $nouvligne;
		}
	
		fclose($data);
		return new Response($reponse);
	}

	//data.adj
	public function simplifierdataadjAction()
	{
		$reponse = '';
		$data = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adj','r');
		$symbols = array('&','!');
		
		//On parcourt le fichier data.adj ligne par ligne
		while ($ligne = fgets($data))
		{
			//On met d'abord la définition de côté
			$tab = explode('|',$ligne);
			$def = ' | ' . $tab[1];
			//Puis on sépare chaque élément et lorsqu'on rencontre un symbole qu'on souhaite traiter on l'ajoute
			$tab = explode(' ',$tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($cle == 0) { $nouvligne = $valeur . ' '; }
				else if (in_array($valeur,$symbols)) { $nouvligne .= substr($valeur,0,1) . ' ' . $tab[$cle + 1]. ' '; }
			}
			$nouvligne .= $def . '&nbsp;<br/>';
			$reponse .= $nouvligne;
		}
	
		fclose($data);
		return new Response($reponse);
	}

	//data.adv
	public function simplifierdataadvAction()
	{
		$reponse = '';
		$data = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adv','r');
		$symbols = array('!');
		
		//On parcourt le fichier data.adv ligne par ligne
		while ($ligne = fgets($data))
		{
			//On met d'abord la définition de côté
			$tab = explode('|',$ligne);
			$def = ' | ' . $tab[1];
			//Puis on sépare chaque élément et lorsqu'on rencontre un symbole qu'on souhaite traiter on l'ajoute
			$tab = explode(' ',$tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($cle == 0) { $nouvligne = $valeur . ' '; }
				else if (in_array($valeur,$symbols)) { $nouvligne .= substr($valeur,0,1) . ' ' . $tab[$cle + 1]. ' '; }
			}
			$nouvligne .= $def . '&nbsp;<br/>';
			$reponse .= $nouvligne;
		}
	
		fclose($data);
		return new Response($reponse);
	}
}