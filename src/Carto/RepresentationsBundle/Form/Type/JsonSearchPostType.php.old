<?php

namespace Carto\RepresentationsBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class JsonSearchPostType extends AbstractType
{
  public function buildForm( FormBuilderInterface $builder,
                                            array $options )
  {
    $builder->add( 'title', 'text' );
    $builder->add( 'body',  'textarea' );
  }
 
  function getName() {
    return 'JsonSearchPostType';
  }
}
