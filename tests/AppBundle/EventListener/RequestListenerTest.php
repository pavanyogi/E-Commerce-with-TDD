<?php

namespace Tests\AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use AppBundle\EventListener\RequestListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Tests\AppBundle\Service\BaseServiceTest;
use AppBundle\Service\AuthenticateAuthorizeService;

class RequestListenerTest extends BaseServiceTest
{
    /** @var RequestListener */
    private $requestListener;

    protected function setUp()
    {
        parent::setUp();
        $container = self::$kernel->getContainer();
        $this->requestListener = new RequestListener($container->get('monolog.logger.api'));
        $this->requestListener->setServiceContainer($this->serviceContainerMock);
        $this->requestListener->setEntityManager($this->entityManagerInterfaceMock);
        $this->requestListener->setLogger($this->logger);
        $this->requestListener->setTranslator($this->translator);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->requestListener = null;
    }

    /**
     * @dataProvider requestListenerDataProvider
     */
    public function testRequestListener($httpMethod, $requestType, $routeName, $routeParameter, $userName, $roles)
    {
        // Making a mock object of kernel
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->getMock();

        // Configuring the request
        $request = new Request();
        $request->setMethod($httpMethod);
        $request->attributes->set('_route', $routeName);
        if($httpMethod === Request::METHOD_GET) {
            $request->request->set('data', $routeParameter);
        }

        $apiAutheticateAuthorizeServiceMock = $this->getMockBuilder(AuthenticateAuthorizeService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $authenticateResult = [
            'message' => [
                'username' => $userName,
                'roles' => $roles
            ],
            'status' => true
        ];

        // Making mock of the authenticateApiRequest
        $apiAutheticateAuthorizeServiceMock
            ->expects($this->any())
            ->method('authenticateApiRequest')
            ->with($request)
            ->willReturn($authenticateResult);

        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with('app.authenticate_autherize_service')
            ->willReturn($apiAutheticateAuthorizeServiceMock);

        // Disaptchning the event
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(KernelEvents::REQUEST, array($this->requestListener,
            'onKernelRequest'));
        $event = new GetResponseEvent($kernel, $request, $requestType);
        $dispatcher->dispatch(KernelEvents::REQUEST, $event);

        // Making assertion
        if($httpMethod !== Request::METHOD_OPTIONS &&
            $requestType !== HttpKernelInterface::SUB_REQUEST &&
            $routeName !== 'agent_login') {
            $this->assertEquals($userName, $request->attributes->get('username'));
            $this->assertEquals(explode(",", $roles), $request->attributes->get('roles'));
        } else {
            $this->assertTrue(TRUE);
        }
    }

    public function requestListenerDataProvider() {
        $eventListenerTest = new EventListenerTestCase();
        $requestListenerTestCase = $eventListenerTest->requestListenerTestCase();

        return $requestListenerTestCase;
    }
}