<?php
/**
 * Controleur pour les recherches DbPedia
 *
 *
 * @author Rémy Cluze <Remy.Cluze@etu.univ-savoie.fr>
 * @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
 * @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
 * @author Rémi Mollard <Remi.Mollard@etu.univ-savoie.fr>
 * @author Céline de Roland <Celine.de-Roland@etu.univ-savoie.fr>
 *
 * @version 1.0
 */

namespace Carto\DonneesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Carto\DonneesBundle\Entity\DBPedia\DBFormateur;

/**
 * Controleur pour les recherches DbPedia
 */
class DbPediaController extends Controller
{

	/**
	 * fonction index
	 *
	 * Effectue une recherche simple : pour un mot donné, on trouve ses relations directes, ses synsets et les relations directes de ces synsets
	 *
	 * @param string $recherche
	 * @param integer $limite nombre maximal de résultats
	 * @return Vue twig
	*/
	public function indexAction($recherche,$limite)
	{

		//La requête sparql
		$sparql = 
'
SELECT DISTINCT * WHERE 
{
 ?sujet rdfs:label "'.ucfirst($recherche).'"@en .
 ?sujet ?property ?objet .
 ?objet ?property2 ?objet2 .
 ?objet2 ?property3 ?objet3 .
 FILTER NOT EXISTS { 
  ?sujet owl:sameAs ?objet .  
 }
 FILTER NOT EXISTS { 
  ?objet owl:sameAs ?objet2 . 
 }
 FILTER NOT EXISTS { 
  ?objet2 owl:sameAs ?objet3 .
 }
} LIMIT '.$limite.'
';
		//L'adresse du moteur sur lequel on doit réaliser cette requête
		$searchUrl = 'http://dbpedia.org/sparql?'
				.'query='.urlencode($sparql)
				.'&format=json';
		//On utilise CURL pour effectuer la requête
		$curl_session = curl_init();
		curl_setopt($curl_session,CURLOPT_URL,$searchUrl);
		curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
		$response = curl_exec($curl_session);
		curl_close($curl_session);

		//La requête renvoie une chaine de caractère, dont la partie 'results'->'bindings' correspond aux résultats
		$tableau = json_decode($response,true);
		$resultats = $tableau['results']['bindings'];

		//On utilise le formateur du modèle, dont le rôle est de transformer le résultat fourni par le moteur en un json au format générique.
		$DbFormateur = new DBFormateur();
		$jsoncommun = $DbFormateur -> transformer($resultats);

		//On transforme le tableau en chaine de caractère json
		$json = json_encode($jsoncommun);

		//On envoie la réponse
		return new Response($json);
	}

}
