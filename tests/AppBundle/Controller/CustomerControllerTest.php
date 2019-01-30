<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpFoundation\Response;

class CustomerControllerTest extends BaseControllerTest
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetCustomerListAction()
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::GET_CUSTOMER_LIST_URL]['method'],
            GeneralConstants::GET_CUSTOMER_LIST_URL,
            array(),
            array(),
            [
                'HTTP_Content-Type' => 'application/json',
                'HTTP_Date' => new \DateTime('now', new \DateTimeZone('UTC')),
                'HTTP_username' => 'testuser',
                'HTTP_Authorization' => 'qwertyuiopasdf'
            ],
            []
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'),
            'Invalid JSON response');
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
            [
                'HTTP_Content-Type' => 'application/json',
                'HTTP_Date' => new \DateTime('now', new \DateTimeZone('UTC')),
                'HTTP_username' => 'testuser',
                'HTTP_Authorization' => 'qwertyuiopasdf'
            ],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'),
            'Invalid JSON response');
    }

    public function getCustomerDetailActionDataProvider() {
        $customerDetailActionTestCases = (new ControllerTestCase())->getCustomerDetailActionTestCases();

        return $customerDetailActionTestCases;
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
            [
                'HTTP_Content-Type' => 'application/json',
                'HTTP_Date' => new \DateTime('now', new \DateTimeZone('UTC')),
                'HTTP_username' => 'testuser',
                'HTTP_Authorization' => 'qwertyuiopasdf'
            ],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'),
            'Invalid JSON response');
    }

    public function createCustomerActionDataProvider() {
        $createCustomerActionTestCases = (new ControllerTestCase())->createCustomerActionTestCases();

        return $createCustomerActionTestCases;
    }
}
