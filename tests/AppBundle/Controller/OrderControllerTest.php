<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Constants\GeneralConstants;

class OrderControllerTest extends BaseControllerTest
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @dataProvider placeOrderActionTestDataProvider
     */
    public function testPlaceOrderAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::PLACE_ORDER_URL]['method'],
            GeneralConstants::PLACE_ORDER_URL,
            array(),
            array(),
            [
                'HTTP_Content-Type' => 'application/json',
                'HTTP_Date' => new \DateTime('now', new \DateTimeZone('UTC')),
                'HTTP_username' => 'testuser',
                'HTTP_Authorization' => 'qwertyuiopasdf'
            ],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'),
            'Invalid JSON response');
        $this->assertNotEmpty($response->getContent());
    }

    public function placeOrderActionTestDataProvider()
    {
        $placeOrderActionTestCases = (new ControllerTestCase())->getPlaceOrderActionTestCases();

        return $placeOrderActionTestCases;
    }
}
