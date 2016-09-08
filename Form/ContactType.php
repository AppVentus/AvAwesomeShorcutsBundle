<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom'])
            ->add('email')
            ->add('message', 'textarea');
    }

    public function getName()
    {
        return 'contact';
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppVentus\Awesome\ShortcutsBundle\Entity\Message',
        ];
    }
}
