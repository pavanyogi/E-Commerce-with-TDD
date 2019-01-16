<?php

namespace AppBundle\EventListener;

use AppBundle\Service\BaseService;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener extends BaseService
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
    }
}