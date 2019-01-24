<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpFoundation\Response;

class AgentControllerTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->client = null;
    }

    /**
     * @dataProvider getLoginActionTestDataProvider
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
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), 'Invalid JSON response');
        $this->assertNotEmpty($response->getContent());
    }

    public function getLoginActionTestDataProvider()
    {
        $requestContent = [];
        $requestContent['credentials'] = [
            'username' => 'superadmin',
            'password' => '123'
        ];

        $expectedStatusCode = Response::HTTP_OK;

        return [
            [$requestContent, $expectedStatusCode]
        ];
    }
}
