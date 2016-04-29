<?php

namespace AppVentus\Awesome\ShortcutsBundle\Form\Type;

use AppVentus\Awesome\ShortcutsBundle\Date\MomentFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class BasePickerType (to factorize DatePickerType and DateTimePickerType code.
 *
 *
 * @author Hugo Briand <briand@ekino.com>
 */
abstract class BasePickerType extends AbstractType
{
    /**
     * @var MomentFormatConverter
     */
    private $formatConverter;

    /**
     * @param MomentFormatConverter $formatConverter
     */
    public function __construct(MomentFormatConverter $formatConverter)
    {
        $this->formatConverter = $formatConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $format = $this->getDefaultFormat();
        if (isset($options['date_format']) && is_string($options['date_format'])) {
            $format = $options['date_format'];
        } elseif (isset($options['format']) && is_string($options['format'])) {
            $format = $options['format'];
        }

        $view->vars['moment_format'] = $this->formatConverter->convert($format);

        $view->vars['type'] = 'text';

        $dpOptions = [];
        foreach ($options as $key => $value) {
            if (false !== strpos($key, 'dp_')) {
                // We remove 'dp_' and camelize the options names
                $dpKey = substr($key, 3);
                $dpKey = preg_replace_callback('/_([a-z])/', function ($c) {
                    return strtoupper($c[1]);
                }, $dpKey);
                if ($value !== null) {
                    $dpOptions[$dpKey] = $value;
                }
            }
        }

        $view->vars['datepicker_use_button'] = empty($options['datepicker_use_button']) ? false : true;
        $view->vars['dp_options'] = $dpOptions;
    }

    /**
     * Gets base default options for the date pickers.
     *
     * @return array
     */
    protected function getCommonDefaults()
    {
        return [
            'widget'                   => 'single_text',
            'datepicker_use_button'    => true,
            'dp_pick_time'             => true,
            'dp_use_current'           => true,
            'dp_min_date'              => '1/1/1900',
            'dp_max_date'              => null,
            'dp_show_today'            => true,
            'dp_view_mode'             => 'days',
            'dp_language'              => 'fr',
            'dp_picker_position'       => null,
            'dp_default_date'          => '',
            'dp_start_view'            => null,
            'dp_min_view'              => null,
            'dp_autoclose'             => false,
            'dp_format'                => 'dd/mm/yyyy hh:ii',
            'dp_disabled_dates'        => [],
            'dp_enabled_dates'         => [],
            'dp_icons'                 => [
                'time' => 'fa fa-times',
                'date' => 'fa fa-calendar',
                'up'   => 'fa fa-chevron-up',
                'down' => 'fa fa-chevron-down',
            ],
            'dp_use_strict'            => false,
            'dp_side_by_side'          => false,
            'dp_days_of_week_disabled' => [],
        ];
    }

    /**
     * Returns default format for type.
     *
     * @return string
     */
    abstract protected function getDefaultFormat();
}
