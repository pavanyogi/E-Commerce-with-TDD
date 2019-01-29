<?php

namespace Tests\AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use AppBundle\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Tests\AppBundle\Service\BaseServiceTest;

class ResponseListenerTest extends BaseServiceTest
{
    /** @var ResponseListener */
    private $responseListener;

    protected function setUp()
    {
        parent::setUp();
        $container = self::$kernel->getContainer();
        $this->responseListener = new ResponseListener($container->get('monolog.logger.api'));
        $this->responseListener->setServiceContainer($this->serviceContainerMock);
        $this->responseListener->setEntityManager($this->entityManagerInterfaceMock);
        $this->responseListener->setLogger($this->logger);
        $this->responseListener->setTranslator($this->translator);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->responseListener = null;
    }

    /**
     * @dataProvider responseListenerDataProvider
     */
    public function testResponseListener($requsetType) {
        // Mocking the kernel
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();

        // Creating the response
        $response = new Response('foo');

        // Creating the request
        $request = new Request();

        // Dispatching the event
        $event = new FilterResponseEvent($kernel, $request, $requsetType,
            $response);
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(KernelEvents::RESPONSE, array($this->responseListener, 'onKernelResponse'));
        $dispatcher->dispatch(KernelEvents::RESPONSE, $event);
        $this->assertEquals('', $event->getResponse()->headers->get('content-type'));
    }

    public function responseListenerDataProvider() {
        // Making first test case (Does Nothing For SubRequests)
        $testData0 = [
            $requestType = HttpKernelInterface::SUB_REQUEST
        ];

        // Making second test case
        $testData1 = [
            $requestType = HttpKernelInterface::MASTER_REQUEST
        ];

        // Making array of testcase and returning it
        return [
            $testData0,
            $testData1
        ];
    }
}