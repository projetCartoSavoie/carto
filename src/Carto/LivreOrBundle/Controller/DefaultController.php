<?php
/**
	* Controleur du livre d'or
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
namespace Carto\LivreOrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Carto\LivreOrBundle\Entity\Commentaire;

/**
 * Controleur du livre d'or
 *
 * Appelle la vue du livre d'or et utilise le modèle pour trouver les commentaires, les enregistrer ou les supprimer.
 */
class DefaultController extends Controller
{

/**
 * Création d'un formulaire
 *
 * @param Commentaire $comment
 * @return FormBuilder
 */
	public function formulaire($comment)
	{
		$formBuilder = $this -> createFormBuilder($comment);
		$formBuilder -> add('auteur','text');
		$formBuilder -> add('contenu','textarea');
		$formBuilder -> add('captcha', 'captcha');
		return $formBuilder;
	}

/**
 * Fonction principale du livre d'or
 *
 * Crée et si besoin enregistre le formulaire d'ajout de commentaire.
 * Demande au modèle tous les commentaires afin de les afficher
 *
 * @return Vue Twig
 */
	public function indexAction()
	{
		$manager = $this -> getDoctrine() -> getManager();
		$request = $this->get('request');

		$comment = new Commentaire();
		$form = $this -> formulaire($comment) -> getForm();
		if ($request->getMethod() == 'POST') 
		{
			$form -> bind($request);
			if ($form->isValid()) 
			{
				$manager -> persist($comment);
				$manager -> flush();
			}
		}

		$form = $form -> createView();

		$com_rep = $manager -> getRepository('CartoLivreOrBundle:Commentaire');
		$comments = $com_rep -> findBy(array(),array('date'=>'desc'));
		return $this -> render('CartoLivreOrBundle:Default:index.html.twig', array('form' => $form, 'comments' => $comments));
	}

/**
 * Fonction d'administration
 *
 * Demande au modèle tous les commentaires afin de les afficher
 * Appelle la vue administrateur afin de pouvoir supprimer des commentaires
 *
 * @return Vue Twig
 */
	public function adminAction()
	{
		$manager = $this -> getDoctrine() -> getManager();
		$com_rep = $manager -> getRepository('CartoLivreOrBundle:Commentaire');
		$comments = $com_rep -> findBy(array(),array('date'=>'desc'));
		return $this -> render('CartoLivreOrBundle:Default:admin.html.twig', array('comments' => $comments));
	}

/**
 * Fonction de suppression
 *
 * Demande au modèle d'effacer le commentaire sélectionné
 * Appelle la vue administrateur
 *
 * @param integer $id : identificateur du commentaire à supprimer
 * @return Vue Twig
 */
	public function supprimerAction($id)
	{
		$manager = $this -> getDoctrine() -> getManager();
		$com_rep = $manager -> getRepository('CartoLivreOrBundle:Commentaire');
		$comment = $com_rep -> find($id);
		$manager -> remove($comment);
		$manager -> flush();
		$comments = $com_rep -> findBy(array(),array('date'=>'desc'));
		return $this -> render('CartoLivreOrBundle:Default:admin.html.twig', array('comments' => $comments));
	}
}
