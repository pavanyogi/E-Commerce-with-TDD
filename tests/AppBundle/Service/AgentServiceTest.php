<?php
namespace Tests\AppBundle\Service;

use AppBundle\Service\AgentService;
use AppBundle\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class AgentServiceTest extends BaseServiceTest
{
    /** @var AgentService */
    private $agentService;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
    }

    protected function setUp()
    {
        parent::setUp();
        $this->agentService = new AgentService();
        $this->agentService->setEntityManager($this->entityManagerInterfaceMock);
        $this->agentService->setLogger($this->logger);
        $this->agentService->setTranslator($this->translator);
        $this->agentService->setServiceContainer($this->serviceContainerMock);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->agentService = null;
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

        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with('fos_user.user_manager')
            ->willReturn($this->userManagerMock);

        $result = $this->agentService->getUser($inputParameter['username']);
        $this->assertEquals($result, $user);
    }

    public function getUserDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $userTestCases = $serviceTest->getUserTestCase();

        return $userTestCases;
    }

    /**
     * @dataProvider getInvalidCredentialUserDataProvider
     */
    public function testValidateUserCredentialsThroughExceptionWithInvalidCredential($credentials, $user)
    {
        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with($this->anything())
            ->will($this->returnCallback(
                function($containerName) use($user, $credentials) {

                    if ($containerName === 'fos_user.user_manager') {
                        $this->userManagerMock
                            ->expects($this->any())
                            ->method('findUserBy')
                            ->with(['username' => $credentials['username']])
                            ->willReturn($user);

                        return $this->userManagerMock;
                    }

                    if ($containerName === 'security.encoder_factory') {
                        $encoder = $this->getMockBuilder(PasswordEncoderInterface::class)
                            ->disableOriginalConstructor()
                            ->getMock();
                        $encoder->expects($this->any())
                            ->method('encodePassword')
                            ->with($credentials['password'], $user->getSalt())
                            ->willReturn('****');
                        $this->encoderFactoryMock
                            ->expects($this->any())
                            ->method('getEncoder')
                            ->with($user)
                            ->willReturn($encoder);

                        return $this->encoderFactoryMock;
                    }
                }
            ));
        $this->expectException(UnprocessableEntityHttpException::class);

        $this->agentService->validateUserCredentials($credentials);
    }

    public function getInvalidCredentialUserDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $userTestCases = $serviceTest->getInvalidCredentialUserTestCase();

        return $userTestCases;
    }

    /**
     * @dataProvider getValidateUserCredentialsDataProvider
     */
    public function testValidateUserCredentials($credentials, $user)
    {
        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with($this->anything())
            ->will($this->returnCallback(
                function($containerName) use($user, $credentials) {

                    if ($containerName === 'fos_user.user_manager') {
                        $this->userManagerMock
                            ->expects($this->any())
                            ->method('findUserBy')
                            ->with(['username' => $credentials['username']])
                            ->willReturn($user);

                        return $this->userManagerMock;
                    }

                    if ($containerName === 'security.encoder_factory') {
                        $encoder = $this->getMockBuilder(PasswordEncoderInterface::class)
                            ->disableOriginalConstructor()
                            ->getMock();
                        $encoder->expects($this->any())
                            ->method('encodePassword')
                            ->with($credentials['password'], $user->getSalt())
                            ->willReturn($user->getPassword());
                        $this->encoderFactoryMock
                            ->expects($this->any())
                            ->method('getEncoder')
                            ->with($user)
                            ->willReturn($encoder);

                        return $this->encoderFactoryMock;
                    }
                }
            ));

        $result = $this->agentService->validateUserCredentials($credentials);
        $this->assertSame($result['message']['user'], $user);
    }

    public function getValidateUserCredentialsDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $userTestCases = $serviceTest->getValidateUserCredentialsTestCase();

        return $userTestCases;
    }

    /**
     * @dataProvider getValidateUserCredentialsWithInvalidUserNameDataProvider
     */
    public function testValidateUserCredentialsThroughExceptionWithInvalidUserName($credentials)
    {
        $this->userManagerMock
            ->expects($this->any())
            ->method('findUserBy')
            ->with(['username' => $credentials['username']])
            ->willReturn(null);
        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with('fos_user.user_manager')
            ->willReturn($this->userManagerMock);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->agentService->validateUserCredentials($credentials);
    }

    public function getValidateUserCredentialsWithInvalidUserNameDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $userTestCases = $serviceTest->getValidateUserCredentialsForInvalidUserNameTestCase();

        return $userTestCases;
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
        $this->serviceContainerMock->expects($this->any())
            ->method('get')
            ->with('fos_user.user_manager')
            ->willReturn($this->userManagerMock);
        $this->expectException(UnprocessableEntityHttpException::class);

        $this->agentService->validateUserCredentials($credentials);

    }

    public function getDisabledUserDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $userTestCases = $serviceTest->getDisabledUserTestCase();

        return $userTestCases;
    }

    public function testGenerateAuthenticationToken()
    {
        $authenticationToken = $this->agentService->generateAuthenticationToken();
        $this->assertNotNull($authenticationToken);
    }

    /**
     * @dataProvider createAuthenticationTokenForUserDataProvider
     */
    public function testCreateAuthenticationTokenForUser($user)
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->anything())
            ->willReturn(null);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($userRepositoryMock);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->agentService->createAuthenticationTokenForUser($user);
        $this->assertNotNull($result['message']['token']);
    }

    public function createAuthenticationTokenForUserDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $userTestCases = $serviceTest->createAuthenticationTokenForUserTestCase();

        return $userTestCases;
    }
}