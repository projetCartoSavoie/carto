<?php
/**
 * Controleur pour l'enregistrement de la base de données WordNet
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
use Carto\DonneesBundle\Entity\WN\ASynset;
use Carto\DonneesBundle\Entity\WN\RSynset;
use Carto\DonneesBundle\Entity\WN\NSynset;
use Carto\DonneesBundle\Entity\WN\VSynset;
use Carto\DonneesBundle\Entity\WN\Mot;

/**
 * Controleur pour l'enregistrement de la base de données WordNet
 *
 * Nous avons enregistré petit à petit une base de données sql représentant les
 * données de WordNet à partir des fichiers texts fournis par WordNet.
 * Ce fichier nous a donc servi une seule fois au moment de construire la base
 * de données sql.
 */
class EnregistreWNController extends Controller
{

/**
 * Affiche les liens pour enregistrer toutes les parties de la base de données
 *
 * @return vue twig
 */
	public function indexAction()
	{
		return $this->render('CartoDonneesBundle:EnregistreWN:index.html.twig');
	}

/**
 * Enregistre des synsets
 *
 * @param string $nom
 * @param string $type
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function sauvegardeAction($nom,$type,$liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/".$nom,"r");
		$classe = "Carto\DonneesBundle\Entity\WN\\".$type.'Synset';
		while ($ligne = fgets($data))
		{
			$tab = explode(' ',$ligne,2);
			$wnid = $tab[0];
			if (intval($wnid) >= $liminf and intval($wnid) < $limsup )
			{
				echo $wnid.' : ';
				$tab2 = explode('|',$tab[1]);
				$def = $tab2[1];
				echo $def.'<br/>';
				$syn = new $classe($wnid,$def);
				$manager -> persist($syn);
			}
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des mots
 *
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function sauvegardemotAction($liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$nrep = $manager ->getRepository('CartoDonneesBundle:WN\NSynset');
		$arep = $manager ->getRepository('CartoDonneesBundle:WN\ASynset');
		$vrep = $manager ->getRepository('CartoDonneesBundle:WN\VSynset');
		$rrep = $manager ->getRepository('CartoDonneesBundle:WN\RSynset');
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/index.words","r");
		$cpt = 0;
		while ($ligne = fgets($data))
		{
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$tab = explode(' ',$ligne);
				echo $tab[0].'<br/>';
				$mot = new Mot($tab[0]);
				foreach($tab as $cle => $valeur)
				{
					if ($cle > 0)
					{
						if ($valeur == 'a') { $rep = $arep; $fonction = 'addAsynset';}
						else if ($valeur == 'n') { $rep = $nrep; $fonction = 'addNsynset';}
						else if ($valeur == 'r') { $rep = $rrep; $fonction = 'addRsynset';}
						else if ($valeur == 'v') { $rep = $vrep; $fonction = 'addVsynset';}
						else if (strlen($valeur) == 8){ 
							//echo $valeur . '<br/>';
							$syn = $rep -> findOneByWnid($valeur);
							//var_dump($syn -> getDefinition());
							$mot -> $fonction($syn);
						}
					}
				}
				$manager -> persist($mot);
			}
			$cpt++;
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre mots
 *
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function deriveAction($liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\Mot');
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/derivation","r");
		$cpt=0;
		while ($ligne = fgets($data))
		{
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$tab = explode(',',$ligne);
				echo strtolower($tab[0]).' -- '.strtolower($tab[1]).'<br/>';
				$mot1 = $rep -> findOneByMot(strtolower($tab[0]));
				$mot2 = $rep -> findOneByMot(substr(strtolower($tab[1]),0,-1));
				$mot1 -> addDeriveFrom($mot2);
				$manager -> persist($mot1);
				$manager -> persist($mot2);
			}
			$cpt++;
		}
		fclose($data);
		$manager -> flush();
		return new Response('<body>enregistré</body>');
	}

/**
 * Enregistre des relations entre mots
 *
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function builtfromAction($liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\Mot');
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/builtfrom","r");

		$cpt = 0;
		while ($ligne = fgets($data))
		{
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$tab = explode(',',$ligne);
				echo strtolower($tab[0]).' -- '.strtolower($tab[1]).'<br/>';
				$mot1 = $rep -> findOneByMot(strtolower($tab[0]));
				$mot2 = $rep -> findOneByMot(substr(strtolower($tab[1]),0,-1));
				echo $mot1 -> getMot().' -- '.$mot2 -> getMot().'<br/>';
				$mot1 -> setBuiltFrom($mot2);
				$manager -> persist($mot1);
				$manager -> persist($mot2);
			}
			$cpt++;
		}
		fclose($data);
		$manager -> flush();
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre mots
 *
 * @return HTMLResponse
 */
	public function participleAction()
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\Mot');
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/participle","r");

		while ($ligne = fgets($data))
		{
			$tab = explode(',',$ligne);
			echo strtolower($tab[0]).' -- '.strtolower($tab[1]).'<br/>';
			$mot1 = $rep -> findOneByMot(strtolower($tab[0]));
			$mot2 = $rep -> findOneByMot(substr(strtolower($tab[1]),0,-1));
			$mot1 -> setParticipleOf($mot2);
			$manager -> persist($mot1);
			$manager -> persist($mot2);
		}
		fclose($data);
		$manager -> flush();
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre mots
 *
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function pertainymAction($liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\Mot');
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/pertainym","r");
		$cpt = 0;
		while ($ligne = fgets($data))
		{
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$tab = explode(',',$ligne);
				echo strtolower($tab[0]).' -- '.strtolower($tab[1]).'<br/>';
				$mot1 = $rep -> findOneByMot(strtolower($tab[0]));
				$mot2 = $rep -> findOneByMot(substr(strtolower($tab[1]),0,-1));
				$mot1 -> addPertainFrom($mot2);
				$manager -> persist($mot1);
				$manager -> persist($mot2);
			}
			$cpt++;
		}
		fclose($data);
		$manager -> flush();
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @param string $nom
 * @param string $type
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function antonymsAction($nom,$type,$liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		if ($type == 'N') { $rep = $manager ->getRepository('CartoDonneesBundle:WN\NSynset'); }
		else if ($type == 'A') { $rep = $manager ->getRepository('CartoDonneesBundle:WN\ASynset'); }
		else if ($type == 'R') { $rep = $manager ->getRepository('CartoDonneesBundle:WN\RSynset'); }
		else if ($type == 'V') { $rep = $manager ->getRepository('CartoDonneesBundle:WN\VSynset'); }
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/".$nom,"r");
		$cpt = 0;
		while ($ligne = fgets($data))
		{
			echo $cpt.'<br/>';
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$antos = array();
				$tab = explode(' ',$ligne);
				$syn_src = $rep -> findOneByWnid($tab[0]);
				foreach($tab as $cle => $valeur)
				{
					if ($valeur == '!') 
					{ 
						if (!in_array($tab[$cle+1],$antos))
						{
							$syn_dest = $rep -> findOneByWnid($tab[$cle+1]);
							$syn_src -> addAntonym($syn_dest);
							$antos[] = $tab[$cle+1];
						}
					}
				}
			}
			$cpt++;
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function hypernymsAction($liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\NSynset'); 
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/mydata.noun","r");
		$cpt = 0;
		while ($ligne = fgets($data))
		{
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$tab = explode(' ',$ligne);
				$syn_src = $rep -> findOneByWnid($tab[0]);
				foreach($tab as $cle => $valeur)
				{
					if ($valeur == '@') 
					{ 
						$syn_dest = $rep -> findOneByWnid($tab[$cle+1]);
						$syn_src -> addHypernym($syn_dest);
					}
				}
			}
			$cpt++;
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function meronymsAction($liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\NSynset'); 
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/mydata.noun","r");
		$cpt = 0;
		while ($ligne = fgets($data))
		{
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$tab = explode(' ',$ligne);
				$syn_src = $rep -> findOneByWnid($tab[0]);
				foreach($tab as $cle => $valeur)
				{
					if ($valeur == '%') 
					{ 
						$syn_dest = $rep -> findOneByWnid($tab[$cle+1]);
						$syn_src -> addMeronym($syn_dest);
					}
				}
			}
			$cpt++;
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @param int $liminf
 * @param int $limsup
 * @return HTMLResponse
 */
	public function attributesAction($liminf,$limsup)
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\NSynset'); 
		$arep = $manager -> getRepository('CartoDonneesBundle:WN\ASynset');
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/mydata.noun","r");
		$cpt = 0;
		while ($ligne = fgets($data))
		{
			if ($cpt >= $liminf and $cpt < $limsup)
			{
				$tab = explode(' ',$ligne);
				$syn_src = $rep -> findOneByWnid($tab[0]);
				foreach($tab as $cle => $valeur)
				{
					if ($valeur == '=') 
					{ 
						echo $tab[0] . ' -- ' . $tab[$cle + 1] . '<br/>';
						$syn_dest = $arep -> findOneByWnid($tab[$cle+1]);
						$syn_src -> addHasAttribute($syn_dest);
					}
				}
			}
			$cpt++;
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @return HTMLResponse
 */
	public function troponymsAction()
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\VSynset'); 
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/mydata.verb","r");
		while ($ligne = fgets($data))
		{
			$tab = explode(' ',$ligne);
			$syn_src = $rep -> findOneByWnid($tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '@') 
				{ 
					echo $tab[0] . ' -- ' . $tab[$cle + 1] . '<br/>';
					$syn_dest = $rep -> findOneByWnid($tab[$cle+1]);
					$syn_src -> addTroponym($syn_dest);
				}
			}
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @return HTMLResponse
 */
	public function entailsAction()
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\VSynset'); 
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/mydata.verb","r");
		while ($ligne = fgets($data))
		{
			$tab = explode(' ',$ligne);
			$syn_src = $rep -> findOneByWnid($tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '*') 
				{ 
					$syn_dest = $rep -> findOneByWnid($tab[$cle+1]);
					$syn_src -> addEntail($syn_dest);
				}
			}
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @return HTMLResponse
 */
	public function causesAction()
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\VSynset'); 
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/mydata.verb","r");
		while ($ligne = fgets($data))
		{
			$tab = explode(' ',$ligne);
			$syn_src = $rep -> findOneByWnid($tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '>') 
				{ 
					$syn_dest = $rep -> findOneByWnid($tab[$cle+1]);
					$syn_src -> addCause($syn_dest);
				}
			}
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}

/**
 * Enregistre des relations entre synsets
 *
 * @return HTMLResponse
 */
	public function similarsAction()
	{
		$manager = $this->getDoctrine()->getManager();
		$rep = $manager ->getRepository('CartoDonneesBundle:WN\ASynset'); 
		$data = fopen("../src/Carto/DonneesBundle/Resources/dict/mydata.adj","r");
		while ($ligne = fgets($data))
		{
			$tab = explode(' ',$ligne);
			$syn_src = $rep -> findOneByWnid($tab[0]);
			foreach($tab as $cle => $valeur)
			{
				if ($valeur == '&') 
				{ 
					echo $tab[0] . ' -- ' . $tab[$cle + 1] . '<br/>';
					$syn_dest = $rep -> findOneByWnid($tab[$cle+1]);
					$syn_src -> addSimilar($syn_dest);
				}
			}
		}
		$manager -> flush();
		fclose($data);
		return new Response('enregistré');
	}
}
