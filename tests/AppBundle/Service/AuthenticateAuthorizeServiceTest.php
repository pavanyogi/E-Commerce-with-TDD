<?php
namespace tests\PhpunitBundle\Service;

use AppBundle\Service\AuthenticateAuthorizeService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManager;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpFoundation\Request;

class AuthenticateAuthorizeServiceTest extends KernelTestCase
{
    /** @var UserManager|PHPUnit_Framework_MockObject_MockObject */
    private $userManagerMock;
    /** @var EntityManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $entityManagerInterfaceMock;
    /** @var AuthenticateAuthorizeService */
    private $authenticateAutherizeService;

    protected function setUp()
    {
        parent::setUp();
        $this->userManagerMock = $this->getMockBuilder(UserManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManagerInterfaceMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->authenticateAutherizeService = new AuthenticateAuthorizeService();
        $this->authenticateAutherizeService->setServiceContainer($container->get('service_container'));
        $this->authenticateAutherizeService->setEntityManager($this->entityManagerInterfaceMock);
        $this->authenticateAutherizeService->setLogger($container->get('monolog.logger.exception'));
        $this->authenticateAutherizeService->setTranslator($container->get('translator.default'));
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->userManagerMock = null;
        $this->entityManagerInterfaceMock = null;
        $this->authenticateAutherizeService = null;
    }

    /**
     * @dataProvider authenticateApiRequestDataProvider
     */
    public function testAuthenticateApiRequest($user, $expectedResponse)
    {
        $request = new Request();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Authorization', $user->getAuthenticationToken());
        $request->headers->set('username', $user->getUserName());
        $request->setMethod(Request::METHOD_POST);
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserByUsername')
            ->with($user->getUSerName())
            ->willReturn($user);

        $result = $this->authenticateAutherizeService->authenticateApiRequest($request);
        $this->assertEquals($result, $expectedResponse);
    }

    public function authenticateApiRequestDataProvider()
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $testCases = [];

        // setting first test cases
        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['username' => 'testAuthenticationAuthorization']);
        $expectedResponse = [
            'status' => 1,
            'message' => [
                'username' => 'testAuthenticationAuthorization'
            ]
        ];
        $testCases[] = [$user, $expectedResponse];

        return $testCases;
    }


    public function testAuthenticateApiRequestShouldThrowExceptionForInvalidContentType()
    {
        $request = new Request();
        $request->headers->set('Content-Type', 'application/xml');
        $this->expectException(UnauthorizedHttpException::class);
        $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    public function testAuthenticateApiRequestShouldThrowExceptionForInvalidAuthorization()
    {
        $request = new Request();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Authorization', '123');
        $request->setMethod(Request::METHOD_POST);
        $this->expectException(UnauthorizedHttpException::class);
        $this->authenticateAutherizeService->authenticateApiRequest($request);
    }


    public function testAuthenticateApiRequestShouldThrowExceptionForInvalidUser()
    {
        $request = new Request();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Authorization', 'ABCDFGRTEDRTFY');
        $request->headers->set('username', 'xxxxxxxx');
        $request->setMethod(Request::METHOD_POST);
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserByUsername')
            ->with('xxxxxxxx')
            ->willReturn(null);

        $this->expectException(UnauthorizedHttpException::class);
        $result = $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    public function testAuthenticateApiRequestShouldThrowExceptionForInvalidMethod()
    {
        $request = new Request();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Authorization', 'ABCDFGRTEDRTFY');
        $request->headers->set('username', 'xxxxxxxx');
        $request->setMethod(Request::METHOD_GET);

        $this->expectException(UnauthorizedHttpException::class);
        $result = $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    /**
     * @dataProvider authenticateApiRequestDataProviderForDisabledUser
     */
    public function testAuthenticateApiRequestShouldThrowExceptionForDisabledUser($user)
    {
        $request = new Request();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Authorization', $user->getAuthenticationToken());
        $request->headers->set('username', $user->getUserName());
        $request->setMethod(Request::METHOD_POST);
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserByUsername')
            ->with($user->getUSerName())
            ->willReturn($user);

        $this->expectException(UnauthorizedHttpException::class);
        $this->authenticateAutherizeService->authenticateApiRequest($request);
    }

    public function authenticateApiRequestDataProviderForDisabledUser() {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $testCases = [];

        // setting first test cases
        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['enabled' => false, 'username' => 'testAuthenticationAuthorizationDisabled']);
        $testCases[] = [$user];

        return $testCases;
    }
}