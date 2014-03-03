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
		$reponse = '';
		$correspondances = array();
		$reperesNdata = array(0,5084,10322,16180,22091,27720,32787,37864,43757,48398,53834,59671,64695,69485,74910,80366);

		$index = fopen('../src/Carto/DocumentationBundle/Resources/dict/index.words','r');
		$ndata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.noun','r');
		$vdata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.verb','r');
		$adata = fopen('../src/Carto/DocumentationBundle/Resources/dict/data.adj','r');
		$rdata = fopen('../src/Carto/DocumentationBundle/Resources/dict/index.adv','r');

		while ($nligne = fgets($ndata)) 
		{ 
			$lignesndata[] = $nligne;
		}
		$k=0;
		while ($ligne = fgets($adata))
		{
			$k++;
			$tab = explode(' ',$ligne);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '+')
				{
					$nsynset = $tab[$cle + 1];
					$numAdj = intval(substr($tab[$cle + 3],0,2));
					$numNom = intval(substr($tab[$cle + 3],2,2));
					$i = $reperesNdata[intval(substr($nsynset,0,2))];
					while ($i < 82192) 
					{ 
						$ntab = explode(' ', $lignesndata[$i]);
						if ($ntab[0] == $nsynset)
						{
							$nom = $ntab[2*($numNom + 1)];
							break;
						}
						$i++;
					}
					$correspondances[] = $tab[2*($numAdj + 1)]. ',' . $nom;
				}
			}
		}

		fclose($index);
		fclose($ndata);
		fclose($vdata);
		fclose($adata);
		fclose($rdata);
		$correspondances = array_unique($correspondances);
		$reponse = implode('&nbsp;<br/>',$correspondances);
		return new Response($reponse);
	}
}
