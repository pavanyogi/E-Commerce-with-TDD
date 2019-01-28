<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Constants\GeneralConstants;

class AgentControllerTest extends BaseControllerTest
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        $userRepo = $this->entityManager->getRepository(User::class);
        $controllerTestCases = (new ControllerTestCase())->getLoginTestCases();
        foreach ($controllerTestCases as $controllerTestCase) {
            $user = $userRepo->findOneBy(['username' =>
                $controllerTestCase['requestContent']['credentials']['username']]);
            if($user) {
                $user->setAuthenticationToken(null);
            }
        }
        $this->entityManager->flush();
        parent::tearDown();
    }

    /**
     * @dataProvider loginActionTestDataProvider
     */
    public function testLoginAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::LOGIN_URL]['method'],
            GeneralConstants::LOGIN_URL,
            array(),
            array(),
            ['Content-Type' => 'application/json',
                'Date' => new \DateTime('now', new \DateTimeZone('UTC'))],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'),
            'Invalid JSON response');
        $this->assertNotEmpty($response->getContent());
    }

    public function loginActionTestDataProvider()
    {
        $loginActionTestCases = (new ControllerTestCase())->getLoginTestCases();

        return $loginActionTestCases;
    }
}
