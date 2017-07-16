<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DatePickerType.
 *
 *
 * @author Hugo Briand <briand@ekino.com>
 */
class DatePickerType extends BasePickerType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array_merge($this->getCommonDefaults(), [
            'dp_pick_time' => false,
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'awesome_type_date_picker';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormat()
    {
        return DateType::HTML5_FORMAT;
    }
}
