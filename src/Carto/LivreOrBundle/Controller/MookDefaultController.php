<?php

namespace Carto\LivreOrBundle\Controller;

class MookDefaultControllerTest extends DefaultController
{
	public function formulaire($comment)
	{
		$formBuilder = $this -> createFormBuilder($comment);
		$formBuilder -> add('auteur','text');
		$formBuilder -> add('contenu','textarea');
		return $formBuilder;
	}
}
