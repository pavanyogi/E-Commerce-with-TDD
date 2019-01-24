<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpFoundation\Response;

class CustomerControllerTest extends WebTestCase
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

    public function testGetCustomerListAction()
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::GET_CUSTOMER_LIST_URL]['method'],
            GeneralConstants::GET_CUSTOMER_LIST_URL,
            array(),
            array(),
            ['Content-Type' => 'application/json',
                'Date' => new \DateTime('now', new \DateTimeZone('UTC'))],
            []
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), 'Invalid JSON response');
    }

    /**
     * @dataProvider getCustomerDetailActionDataProvider
     */
    public function testGetCustomerDetailAction($requestContent)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::GET_CUSTOMER_DETAIL_URL]['method'],
            GeneralConstants::GET_CUSTOMER_DETAIL_URL,
            array(),
            array(),
            ['Content-Type' => 'application/json',
                'Date' => new \DateTime('now', new \DateTimeZone('UTC'))],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), 'Invalid JSON response');
    }

    public function getCustomerDetailActionDataProvider() {
        $requestContent = [
            'id' => 1
        ];

        return [
            [$requestContent]
        ];
    }

    /**
     * @dataProvider createCustomerActionDataProvider
     */
    public function testCreateCustomerAction($requestContent)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::CREATE_CUSTOMER_URL]['method'],
            GeneralConstants::CREATE_CUSTOMER_URL,
            array(),
            array(),
            ['Content-Type' => 'application/json',
                'Date' => new \DateTime('now', new \DateTimeZone('UTC'))],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), 'Invalid JSON response');
    }

    public function createCustomerActionDataProvider() {
        $requestContent = [
            'name' => 'Prafulla',
            'phoneNumber' => '9777096808'
        ];

        return [
            [$requestContent]
        ];
    }
}
