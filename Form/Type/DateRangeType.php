<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeType extends AbstractType
{
    protected $translator;

    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start', $options['start_field_type'], array_merge(array('required' => false), $options['start_field_options']));
        $builder->add('end', $options['end_field_type'], array_merge(array('required' => false), $options['end_field_options']));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'awesome_type_date_range';
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'start_field_options' => array(),
            'end_field_options'   => array(),
            'start_field_type'    => 'date',
            'end_field_type'      => 'date',
        ));
    }
}
