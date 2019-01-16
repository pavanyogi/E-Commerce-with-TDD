<?php

namespace AppBundle\Tests\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use AppBundle\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResponseListenerTest extends KernelTestCase
{
    private $dispatcher;
    private $kernel;

    protected function setUp() {
        $this->dispatcher = new EventDispatcher();
        $container = (self::bootKernel())->getContainer();
        $responseListener = new ResponseListener();
        $responseListener->setServiceContainer($container->get('service_container'));
        $responseListener->setEntityManager($container->get('doctrine')->getManager());
        $responseListener->setLogger($container->get('monolog.logger.exception'));
        $responseListener->setTranslator($container->get('translator.default'));
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($responseListener, 'onKernelResponse'));
        $this->kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();
    }

    protected function tearDown() {
        $this->dispatcher = null;
        $this->kernel = null;
    }

    public function testFilterDoesNothingForSubRequests() {
        $response = new Response('foo');
        $event = new FilterResponseEvent($this->kernel, new Request(), HttpKernelInterface::SUB_REQUEST,
            $response);
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        $this->assertEquals('', $event->getResponse()->headers->get('content-type'));
    }
}