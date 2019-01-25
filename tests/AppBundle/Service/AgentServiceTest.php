<?php
namespace Tests\AppBundle\Service;

use AppBundle\Service\AgentService;
use AppBundle\Entity\User;

class AgentServiceTest extends BaseServiceTest
{
    /** @var AgentService */
    private $agentService;

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
        var_dump($serviceTest->getUserTestCase());
        echo 5; die();
        $serviceTest->getUserTestCase();
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $testCases = [];

        // setting first test cases
        $userName['username'] = 'testuser';
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $userName['username']]);

        return [
            [$userName, $user]
        ];
    }
}