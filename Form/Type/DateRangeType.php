<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start', $options['start_field_type'], array_merge(['required' => false], $options['start_field_options']));
        $builder->add('end', $options['end_field_type'], array_merge(['required' => false], $options['end_field_options']));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'awesome_type_date_range';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'start_field_options' => [],
            'end_field_options'   => [],
            'start_field_type'    => 'date',
            'end_field_type'      => 'date',
        ]);
    }
}
