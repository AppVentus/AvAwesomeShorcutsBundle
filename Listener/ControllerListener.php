<?php

namespace AppVentus\Awesome\ShortcutsBundle\Listener;
use Symfony\Component\HttpKernel\HttpKernelInterface; 
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use \Symfony\Component\HttpFoundation\Response;

class ControllerListener 
{	 
    public function preExecuteController(FilterControllerEvent $event)
    {
        // Event catching
        if(HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) 
        {
            // controller catching    
            $_controller = $event->getController();
            if (is_array($_controller) && isset($_controller[0])) 
            {
                $controller = $_controller[0];
                // preExecute method verification
                if(method_exists($controller,'preExecute'))
                {
                    $controller->preExecute();
                }
            }
        }
 
    }
}