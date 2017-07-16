<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatemaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $settings = null;
        if (0 !== count($options['mask_option'])) {
            $settings = json_encode($options['mask_option']);
        }
        $view->vars['datemask_setting'] = $settings;
    }

    /**
     * {@inheritdoc}
     *
     * @see https://github.com/igorescobar/jQuery-Mask-Plugin
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'widget'      => 'single_text',
            'input'       => 'datetime',
            'format'      => 'dd/MM/yyyy',
            'mask_option' => '00/00/0000',
            'help_block'  => 'format : 31/12/1980',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateType::class;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'datemask';
    }
}
