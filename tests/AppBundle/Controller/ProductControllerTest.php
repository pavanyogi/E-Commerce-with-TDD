<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends BaseControllerTest
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
     * @dataProvider getProductListActionDataProvider
     */
    public function testGetProductListAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::GET_PRODUCT_URL]['method'],
            GeneralConstants::GET_PRODUCT_URL,
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

    public function getProductListActionDataProvider()
    {
        $getProductListActionTestCases = (new ControllerTestCase())->getProductListActionTestCases();

        return $getProductListActionTestCases;
    }

    /**
     * @dataProvider getProductDetailActionDataProvider
     */
    public function testGetProductDetailAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::DETAIL_PRODUCT_URL]['method'],
            GeneralConstants::DETAIL_PRODUCT_URL,
            array(),
            array(),
            ['Content-Type' => 'application/json', 'Date' => new \DateTime('now', new \DateTimeZone('UTC'))],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), 'Invalid JSON response');
        $this->assertNotEmpty($response->getContent());
    }

    public function getProductDetailActionDataProvider()
    {
        $getProductDetailActionTestCases = (new ControllerTestCase())->getProductDetailActionTestCases();

        return $getProductDetailActionTestCases;
    }

    /**
     * @dataProvider updateProductActionDataProvider
     */
    public function testUpdateProductAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::UPDATE_PRODUCT_URL]['method'],
            GeneralConstants::UPDATE_PRODUCT_URL,
            array(),
            array(),
            ['Content-Type' => 'application/json', 'Date' => new \DateTime('now', new \DateTimeZone('UTC'))],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), 'Invalid JSON response');
        $this->assertNotEmpty($response->getContent());
    }

    public function updateProductActionDataProvider()
    {
        $updateProductActionTestCases = (new ControllerTestCase())->updateProductActionTestCases();

        return $updateProductActionTestCases;
    }

    /**
     * @dataProvider createProductActionDataProvider
     */
    public function testCreateProductAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::CREATE_PRODUCT_URL]['method'],
            GeneralConstants::CREATE_PRODUCT_URL,
            array(),
            array(),
            ['Content-Type' => 'application/json', 'Date' => new \DateTime('now', new \DateTimeZone('UTC'))],
            json_encode($requestContent)
        );
        $response = $this->client->getResponse();
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), 'Invalid JSON response');
        $this->assertNotEmpty($response->getContent());
    }

    public function createProductActionDataProvider()
    {
        $createProductActionTestCases = (new ControllerTestCase())->createProductActionTestCases();

        return $createProductActionTestCases;
    }
}
