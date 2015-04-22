<?php

namespace AppVentus\Awesome\ShortcutsBundle;

use Monolog\Handler\NullHandler;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AvAwesomeShortcutsBundle extends Bundle
{
    public function boot()
    {
        if ($this->container->getParameter('kernel.debug')) {
            $GLOBALS['logger'] = $this->container->get('logger');
        } else {
            $GLOBALS['logger'] = new NullHandler();
        }
    }
}
