<?php

namespace Carto\DocumentationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EtudeWNController extends Controller
{
	public function indexAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:index.html.twig');
	}

	public function wordsAction()
	{
		return $this->render('CartoDocumentationBundle:EtudeWN:words.html.twig');
	}

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
}
