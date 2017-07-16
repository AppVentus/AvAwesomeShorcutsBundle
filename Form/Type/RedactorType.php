<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RedactorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $settings = null;
        if (array_key_exists('options', $options) && 0 !== count($options['options'])) {
            $settings = json_encode($options['options']);
        }
        $view->vars['redactor_settings'] = $settings;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // @see http://redactorjs.com/docs/settings
        $resolver->setDefaults([
            'options' => [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextareaType::class;;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'redactor';
    }
}
