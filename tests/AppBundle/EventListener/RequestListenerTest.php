<?php

namespace tests\AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use AppBundle\EventListener\RequestListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RequestListenerTest extends KernelTestCase
{
    /**
     * @expectedException \Symfony\Component\HttpFoundation\Exception\ConflictingHeadersException
     */
    public function testListenerThrowsWhenMasterRequestHasInconsistentClientIps()
    {
        $dispatcher = new EventDispatcher();
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();
        $request = new Request();
        $request->setTrustedProxies(array('1.1.1.1'));
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $request->headers->set('FORWARDED', 'for=2.2.2.2');
        $request->headers->set('X_FORWARDED_FOR', '3.3.3.3');
        $container = (self::bootKernel())->getContainer();
        $requestListener = new RequestListener();
        $requestListener->setServiceContainer($container->get('service_container'));
        $requestListener->setEntityManager($container->get('doctrine')->getManager());
        $requestListener->setLogger($container->get('monolog.logger.exception'));
        $requestListener->setTranslator($container->get('translator.default'));
        $dispatcher->addListener(KernelEvents::REQUEST, array($requestListener,
            'onKernelRequest'));
        $event = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);
        $dispatcher->dispatch(KernelEvents::REQUEST, $event);
    }
}