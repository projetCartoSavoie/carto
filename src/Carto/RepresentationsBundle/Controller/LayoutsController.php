<?php

namespace Carto\RepresentationsBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
 
use Carto\RepresentationsBundle\Form\Type\JsonSearchPostType;
 
class LayoutsController extends Controller
{

  /**
   * @Route( "/PLO", name="create_post" )
   * @Template()
   */
  public function layoutAction( Request $request )
  {
	
	$form = $this->createFormBuilder(new JsonSearchPostType())
            ->getForm();

	return array(
      'postform' => $form->createView()
    );
  }
}