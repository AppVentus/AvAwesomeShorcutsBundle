<?php

namespace AppVentus\Awesome\ShortcutsBundle\Twig;

/**
 * Awesome twig extension
 */
class AwesomeExtension extends \Twig_Extension
{

    /**
     * register twig functions
     */
    public function getFunctions()
    {
        return array(
            'period_display' => new \Twig_Function_Method($this, 'periodDisplay', array('is_safe' => array('html'))),
        );
    }

    /**
     * register twig filters
     */
    public function getFilters()
    {
        return array(
            'distance_format' => new \Twig_Filter_Method($this, 'distanceFormat'),
        );
    }

    /**
     * Return the distance with the unit (for example, call distance|distance_format and you will get Xm ou Xkm )
     */
    public function distanceFormat($distance)
    {
        if (number_format($distance, 0) == 0) {
            $distance = number_format($distance, 3) * 1000;
            $unit = "m";
        } else {
            $distance = number_format($distance, 0);
            $unit = "km";
        }

        return $distance.$unit;
    }

    public function periodDisplay($startDate, $endDate)
    {
        setlocale(LC_TIME, 'fr_FR','fra');

        if ($startDate == $endDate) {
            return 'le ' . strftime('%d %B', $startDate->getTimestamp());
        } else {

            if ($startDate->format('Y') != $endDate->format('Y')) {
                return 'du <span>' . strftime('%d %B %Y', $startDate->getTimestamp()) . '</span></span> au <span>' . strftime('%d %B %Y', $endDate->getTimestamp()).'</span>';
            } elseif ($startDate->format('m') != $endDate->format('m')) {
                return 'du <span>' . strftime('%d %B', $startDate->getTimestamp()) . '</span> au <span>' . strftime('%d %B %Y', $endDate->getTimestamp()).'</span>';
            } else {
                return 'du <span>' . strftime('%d', $startDate->getTimestamp()) . '</span> au <span>' . strftime('%d %B %Y', $endDate->getTimestamp()).'</span>';
            }

        }
    }

    /**
     * get extension name
     */
    public function getName()
    {
        return 'awesome';
    }

}
