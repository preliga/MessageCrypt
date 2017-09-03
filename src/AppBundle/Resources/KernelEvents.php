<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-09-03
 * Time: 21:47
 */

namespace AppBundle\Resources;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;

class KernelEvents
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if(!is_array($controller))
        {
            // not a controller do nothing
            return;
        }

        $controllerObject = $controller[0];
        if(is_object($controllerObject) && method_exists($controllerObject,"preAction") )
        {
            $controllerObject->preAction();
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

//        die(dump($response));
    }
}