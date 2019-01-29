<?php

namespace AppBundle\EventListener;

use AppBundle\Service\BaseService;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Psr\Log\LoggerInterface;

class ResponseListener extends BaseService
{
    /**
     * @var LoggerInterface
     */
    private $apiLogger;

    public function __construct(LoggerInterface $logger) {
        $this->apiLogger = $logger;
    }

    /**
     * Function for logging api response.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $responseContent = $event->getResponse()->getContent();
        $request = $event->getRequest();
        $this->apiLogger->debug('API Response',
            array_merge($request->headers->all(),
                [
                    'host' => $request->getSchemeAndHttpHost(),
                    'uri' => $request->getRequestUri(),
                    'method' => $request->getMethod(),
                    'request_content' => $request->getContent(),
                    'response_content' => $responseContent
                ]

            ));
    }
}