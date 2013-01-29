<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContactType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name',null,array('label'=>"Nom"))
            ->add('type', 'choice', array('choices'=>array('commercial'=>"Vous avez une question sur l'organisation de la jounée ?","technic"=>"Vous rencontrez un problème technique sur le site ?")))
            ->add('email')
            ->add('message', 'textarea')
        ;
    }

    public function getName()
    {
        return 'contact';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'AppVentus\Awesome\ShortcutsBundle\Entity\Message',
        );
    }

}