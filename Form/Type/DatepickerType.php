<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatepickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $settings = null;
        if (0 !== count($options['options'])) {
            $settings = json_encode($options['options']);
        }
        $view->vars['datepicker_settings'] = $settings;
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
                'format'    => 'dd/mm/yyyy',
                'weekStart' => 1,
                'autoclose' => true,
            ),
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
        return 'datepicker';
    }

}
