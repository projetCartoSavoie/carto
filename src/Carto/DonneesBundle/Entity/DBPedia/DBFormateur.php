<?php
/**
	* Modèle pour le traitement d'un résultat DBPEDIA
	*
	* @author Rémy Cluze <Remy.Cluze@etu.univ-savoie.fr>
	* @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
	* @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
	* @author Rémi Mollard <Remi.Mollard@etu.univ-savoie.fr>
	* @author Céline de Roland <Celine.de-Roland@etu.univ-savoie.fr>
	*
	* @version 1.0
	*/
namespace Carto\DonneesBundle\Entity\DBPedia;

/**
	* Modèle pour le traitement d'un résultat DBPEDIA
	*
	*/
class DBFormateur
{

/**
	* Transforme les résultats founis par dbpedia et les transforme en json générique
	*
	* @param $resultats : tableau de résultats dbpedia, les résultats fournis sont des branches sujet rel objet rel2 objet2 rel3 objet3
	* @return string : chaine de caractères json générique
	*/
	public function transformer($resultats)
	{
		//Enlever toutes les branches dont un des noeuds contient au moins un littéral qui n'est pas écrit en langue anglaise
		foreach ($resultats as $cle => $chaine) //Un résultat est une chaine d'objets liés par des relations
		{
			if ( ($chaine['sujet']['type'] == 'literal' and isset($chaine['sujet']['xml:lang']) and $chaine['sujet']['xml:lang'] != 'en')
				or ($chaine['objet']['type'] == 'literal' and isset($chaine['objet']['xml:lang']) and $chaine['objet']['xml:lang'] != 'en')
				or ($chaine['objet2']['type'] == 'literal' and isset($chaine['objet2']['xml:lang']) and $chaine['objet2']['xml:lang'] != 'en')
				or ($chaine['objet3']['type'] == 'literal' and isset($chaine['objet3']['xml:lang']) and $chaine['objet3']['xml:lang'] != 'en')
			)
			{
				unset($resultats[$cle]);
			}
		}

		//Etablir la liste des noeuds et des relations :

		//Les identifiants des noeuds commenceront à 1
		$id = 1;
		//Ce tableau deviendra la json générique que nous devons renvoyer aux vues
		$jsoncommun = array();
		//Liste des noeuds
		$lnoeuds = array();
		//Liste des relations
		$lrelations = array();

		//On appellera $chaine un tableau associatif contenant les entrées 'sujet', 'property', 'objet', 'property2', 'objet2', 'property3', 'objet3'. Chacune des entrées étant elle même un tableau contenant des informations (et en particulier l'information 'value') sur resp. le sujet, le premier prédicat, l'objet etc.
		foreach ($resultats as $cle => $chaine) //Un résultat est une chaine d'objets liés par des relations
		{
			//On raccourcit les valeurs des noeuds pour transformer les URI en mots intelligibles
			$resultats[$cle]['sujet']['value'] = $this -> raccourcir($chaine['sujet']['value']);
			$resultats[$cle]['property']['value'] = $this -> raccourcir($chaine['property']['value']);
			$resultats[$cle]['objet']['value'] = $this -> raccourcir($chaine['objet']['value']);
			$resultats[$cle]['property2']['value'] = $this -> raccourcir($chaine['property2']['value']);
			$resultats[$cle]['objet2']['value'] = $this -> raccourcir($chaine['objet2']['value']);
			$resultats[$cle]['property3']['value'] = $this -> raccourcir($chaine['property3']['value']);
			$resultats[$cle]['objet3']['value'] = $this -> raccourcir($chaine['objet3']['value']);

			//On enregistre la liste des noeuds et des relations qui interviendront dans le graphe
			$lnoeuds[] = $resultats[$cle]['sujet']['value'];
			$lrelations[] = $resultats[$cle]['property']['value'];
			$lnoeuds[] = $resultats[$cle]['objet']['value'];
			$lrelations[] = $resultats[$cle]['property2']['value'];
			$lnoeuds[] = $resultats[$cle]['objet2']['value'];
			$lrelations[] = $resultats[$cle]['property3']['value'];
			$lnoeuds[] = $resultats[$cle]['objet3']['value'];
		}
		//var_dump($resultats);

		//On élimine les doublons et on attribut des ids à chaque valeur.
		$lnoeuds = array_unique($lnoeuds);
		$idnoeuds = array_flip($lnoeuds);
		$lrelations = array_unique($lrelations);
		$idrelations = array_flip($lrelations);

		//On place la liste de noeuds dans le json générique
		$jsoncommun['noeuds'] = array();
		foreach($lnoeuds as $cle => $valeur)
		{
			$jsoncommun['noeuds'][] = array('id' => (string)$cle, 'nom' => $valeur);
			$jsoncommun['graphe'][$cle] = array('noeud' => (string)$cle);
		}

		//On place la liste des relations dans le json générique
		$jsoncommun['relations'] = array();
		foreach($lrelations as $valeur)
		{
			$jsoncommun['relations'][] = $valeur;
		}
		
		//On inscrit les relations dans la partie du json générique qui code le graphe.
		foreach ($resultats as $cle => $chaine) //Un résultat est une chaine d'objets liés par des relations
		{
			//Mettre en relation sujet et objet par la relation property
			if (!isset($jsoncommun['graphe'][$idnoeuds[$chaine['sujet']['value']]][$chaine['property']['value']]))
			{
				$jsoncommun['graphe'][$idnoeuds[$chaine['sujet']['value']]][$chaine['property']['value']] = array();
			}
			$jsoncommun['graphe'][$idnoeuds[$chaine['sujet']['value']]][$chaine['property']['value']][] = (string)$idnoeuds[$chaine['objet']['value']];
			//Mettre en relation objet et objet2 par la relation property2
			if (!isset($jsoncommun['graphe'][$idnoeuds[$chaine['objet']['value']]][$chaine['property2']['value']]))
			{
				$jsoncommun['graphe'][$idnoeuds[$chaine['objet']['value']]][$chaine['property2']['value']] = array();
			}
			$jsoncommun['graphe'][$idnoeuds[$chaine['objet']['value']]][$chaine['property2']['value']][] = (string)$idnoeuds[$chaine['objet2']['value']];
			//Mettre en relation objet2 et objet3 par la relation property3
			if (!isset($jsoncommun['graphe'][$idnoeuds[$chaine['objet2']['value']]][$chaine['property3']['value']]))
			{
				$jsoncommun['graphe'][$idnoeuds[$chaine['objet2']['value']]][$chaine['property3']['value']] = array();
			}
			$jsoncommun['graphe'][$idnoeuds[$chaine['objet2']['value']]][$chaine['property3']['value']][] = (string)$idnoeuds[$chaine['objet3']['value']];
		}

		//On enlève les id qui ne sont pas attendues dans le jsoncommun
		$jsoncommun['noeuds'] = array_values($jsoncommun['noeuds']);
		$jsoncommun['graphe'] = array_values($jsoncommun['graphe']);

		return $jsoncommun;
	}

/**
	* Pour simplifier la valeur des noeuds, on transforme les URI du type <http://www.w3.org/2002/07/owl#category> en simplement category
	*
	* @param $chaine : l'URI difficile à lire
	* @return string : chaine de caractères simplifiée
	*/
	public function raccourcir($chaine)
	{
		$tableau = explode("/",$chaine);
		$chaine = array_pop($tableau);
		$tableau = explode("#",$chaine);
		$chaine = array_pop($tableau);
		$tableau = explode(":",$chaine);
		$chaine = array_pop($tableau);
		return $chaine;
	}
}
