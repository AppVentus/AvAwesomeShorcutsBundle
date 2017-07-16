<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        $view->vars['datemask_settings'] = json_encode($options['mask_option']);
    }

    /**
     * {@inheritdoc}
     *
     * @see https://github.com/eternicode/bootstrap-datepicker
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'widget'  => 'single_text',
            'input'   => 'datetime',
            'format'  => 'dd/MM/yyyy',
            'options' => [
                'format'    => 'dd/mm/yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ],
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
        return 'datemaskpicker';
    }
}
