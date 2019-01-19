<?php
namespace tests\PhpunitBundle\Service;

use AppBundle\Service\AgentService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManager;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use AppBundle\Repository\UserRepository;

class AgentServiceTest extends KernelTestCase
{
    /** @var UserManager|PHPUnit_Framework_MockObject_MockObject */
    private $userManagerMock;
    /** @var EntityManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $entityManagerInterfaceMock;
    /** @var AgentService */
    private $agentService;
    /** @var EncoderFactory */
    private $encoderFactoryMock;

    protected function setUp()
    {
        parent::setUp();
        $this->userManagerMock = $this->getMockBuilder(UserManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManagerInterfaceMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->encoderFactoryMock = $this->getMockBuilder(EncoderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->agentService = new AgentService();
        $this->agentService->setServiceContainer($container->get('service_container'));
        $this->agentService->setEntityManager($this->entityManagerInterfaceMock);
        $this->agentService->setLogger($container->get('monolog.logger.exception'));
        $this->agentService->setTranslator($container->get('translator.default'));
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->userManagerMock = null;
        $this->entityManagerInterfaceMock = null;
        $this->agentService = null;
        $this->encoderFactoryMock = null;
    }

    /**
     * @dataProvider getUserDataProvider
     */
    public function testGetUser($inputParameter, $user)
    {
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserBy')
            ->with(['username' => $inputParameter['username']])
            ->willReturn($user);

        $result = $this->agentService->getUser($inputParameter);
        $this->assertEquals($result, $user);
    }

    public function getUserDataProvider()
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $testCases = [];

        // setting first test cases
        $userName['username'] = 'superadmin';
        $user = $entityManager->getRepository(User::class)->findOneBy(['enabled' => true]);

        $testCases[] = [$userName, $user];

        return $testCases;
    }

    /**
     * @dataProvider getValidateUserCredentialsDataProvider
     */
    public function testValidateUserCredentials($credentials, $user, $expectedResult)
    {
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserBy')
            ->with(['username' => $credentials['username']])
            ->willReturn($user);

        $this->encoderFactoryMock
            ->expects($this->any())
            ->method('getEncoder')
            ->with($user)
            ->willReturn($user);

        $result = $this->agentService->validateUserCredentials($credentials);
        $this->assertSame(($result['message']['user'])->getId(), $expectedResult->getId());
    }

    public function getValidateUserCredentialsDataProvider()
    {
        $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => 'superadmin']);
        $testCase = [
            [
                [
                    'username' => 'superadmin',
                    'password' => '123'
                ],
                $user,
                $user
            ]
        ];

        return $testCase;
    }

    /**
     * @dataProvider getValidateUserCredentialsWithInvalidUserNameDataProvider
     */
    public function testValidateUserCredentialsThroughExceptionWithInvalidUserName($credentials, $user)
    {
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserBy')
            ->with(['username' => $credentials['username']])
            ->willReturn(null);

        $this->expectException(UnprocessableEntityHttpException::class);

        $this->encoderFactoryMock
            ->expects($this->any())
            ->method('getEncoder')
            ->with($user)
            ->willReturn($user);

        $result = $this->agentService->validateUserCredentials($credentials);
        $this->expectException(UnprocessableEntityHttpException::class);
    }

    public function getValidateUserCredentialsWithInvalidUserNameDataProvider()
    {
        $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => 'superadmin']);
        $testCase = [
            [
                [
                    'username' => '*********',
                    'password' => '123'
                ],
                $user,
                $user
            ]
        ];

        return $testCase;
    }

    /**
     * @dataProvider getDisabledUserDataProvider
     */
    public function testValidateUserCredentialsThroughExceptionWithDisabledUser($credentials, $user)
    {
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserBy')
            ->with(['username' => $credentials['username']])
            ->willReturn($user);

        $this->encoderFactoryMock
            ->expects($this->any())
            ->method('getEncoder')
            ->with($user)
            ->willReturn($user);
        $this->expectException(UnprocessableEntityHttpException::class);

        $this->agentService->validateUserCredentials($credentials);

    }

    public function getDisabledUserDataProvider()
    {
        $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $disabledUser = $entityManager->getRepository(User::class)->findOneBy(['username' => 'prafullameher',
            'enabled' => 0]);
        $testCase = [
            [
                [
                    'username' => 'prafullameher',
                    'password' => '123'
                ],
                $disabledUser
            ]
        ];

        return $testCase;
    }

    /**
     * @dataProvider getInvalidCredentialUserDataProvider
     */
    public function testValidateUserCredentialsThroughExceptionWithInvalidCredential($credentials, $user)
    {
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserBy')
            ->with(['username' => $credentials['username']])
            ->willReturn($user);

        $this->encoderFactoryMock
            ->expects($this->any())
            ->method('getEncoder')
            ->with($user)
            ->willReturn($user);

        $this->expectException(UnprocessableEntityHttpException::class);

        $result = $this->agentService->validateUserCredentials($credentials);

    }

    public function getInvalidCredentialUserDataProvider()
    {
        $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $disabledUser = $entityManager->getRepository(User::class)->findOneBy(['username' => 'superadmin',
            'enabled' => 1]);
        $testCase = [
            [
                [
                    'username' => 'superadmin',
                    'password' => '*****'
                ],
                $disabledUser
            ]
        ];

        return $testCase;
    }

    /**
     * @dataProvider createAuthenticationTokenForUserDataProvider
     */
    /*public function testCreateAuthenticationTokenForUser($user, $token)
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['authenticationToken' => $token])
            ->willReturn($user);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($userRepositoryMock);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->agentService->createAuthenticationTokenForUser($user);
        $this->assertEquals($result['message']['token'], $token);
    }*/

    public function createAuthenticationTokenForUserDataProvider()
    {
        $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => 'superadmin',
            'enabled' => 1]);
        $token = $user->getAuthenticationToken();
        $testCase = [
            [
                $user,
                $token
            ]
        ];

        return $testCase;
    }

    public function testGenerateAuthenticationToken()
    {
        $authenticationToken = $this->agentService->generateAuthenticationToken();
        $this->assertNotNull($authenticationToken);
    }
}