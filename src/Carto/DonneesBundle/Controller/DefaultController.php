<?php
/**
 * Controleur pour les extractions de données
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

/**
 * Controleur pour les extractions de données
 */
class DefaultController extends Controller
{

	public function extractdebianAction($mot)
	{
		$url = 'https://packages.debian.org/wheezy/'.$mot;
		$curlSession = curl_init();

		curl_setopt($curlSession, CURLOPT_URL, $url);
		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curlSession);
		preg_match('#<div id="pdesc" >(.*)</div>#sU', $result, $matchespar);
		preg_match('#<h1>(.*)</h1>#sU', $result, $matchestitre);
		return new Response('<p><a href="https://packages.debian.org/wheezy/'.$mot.'">source : https://packages.debian.org/wheezy/'.$mot.'</a></p>'.$matchestitre[0].$matchespar[1]);
		
		return $this->render('CartoDonneesBundle:Humour:objet.html.twig', array('form' => $form, 'objets' => $objets));
	}


}
