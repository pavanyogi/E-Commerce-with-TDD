<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends WebTestCase
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
     * @dataProvider getPlaceOrderActionTestDataProvider
     */
    public function testPlaceOrderAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::PLACE_ORDER_URL]['method'],
            GeneralConstants::PLACE_ORDER_URL,
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

    public function getPlaceOrderActionTestDataProvider()
    {
        $requestContent = [];
        $requestContent['orderItems'] = [
            [
                'productCode' => 'P001',
                'quantity' => 2
            ]
        ];
        $requestContent['customerDetails'] = [
            'name' => 'Prafulla Meher',
            'phoneNumber' => '9777096808'
        ];

        $expectedStatusCode = Response::HTTP_OK;

        return [
            [$requestContent, $expectedStatusCode]
        ];
    }
}
