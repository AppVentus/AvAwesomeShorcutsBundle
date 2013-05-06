<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatemaskpickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $settings = null;
        if (0 !== count(array_merge($options['options']))) {
            $settings = json_encode($options['options']);
        }
        $view->vars['datepicker_settings'] = $settings;
        $view->vars['datemask_settings'] = json_encode($options["mask_option"]);
    }

    /**
     * {@inheritdoc}
     * @see https://github.com/eternicode/bootstrap-datepicker
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget'  => 'single_text',
            'input'   => 'datetime',
            'format'  => 'dd/MM/yyyy',
            'options' => array(
                'format' => 'dd/mm/yyyy',
                'autoclose'  => true,
            ),
            'mask_option' => '11/11/1111',
            'help_block'  => 'ex : 31/12/1980',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'date';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'datemaskpicker';
    }

}
