<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProductControllerTest extends WebTestCase
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
     * @dataProvider getTestGetProductListActionDataProvider
     */
    public function testGetProductListAction($requestContent, $expectedStatusCode)
    {
        $this->client->request(
            GeneralConstants::$urlMethodMap[GeneralConstants::GET_PRODUCT_URL]['method'],
            GeneralConstants::GET_PRODUCT_URL,
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

    public function getTestGetProductListActionDataProvider()
    {
        $requestContent0 = [];
        $requestContent0['filter'] = [];
        $requestContent0['pagination'] = ['page' => 1, 'limit' => 2];
        $expectedStatusCode0 = Response::HTTP_OK;

        $requestContent1 = [];
        $requestContent1['filter'] = [
            'productCode' => 'P001',
            'productName' => 'shoes',
            'productDescription' => 'A good shoes',
            'quantity' => 12.00,
            'stockAvialable' => 1
        ];
        $requestContent1['pagination'] = ['page' => 1, 'limit' => 2];
        $expectedStatusCode1 = Response::HTTP_OK;

        return [
            [$requestContent0, $expectedStatusCode0],
            [$requestContent1, $expectedStatusCode1]
        ];
    }

    /**
     * @dataProvider getTestGetProductDetailActionDataProvider
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

    public function getTestGetProductDetailActionDataProvider()
    {
        $requestContent0 = [];
        $requestContent0['productCode'] = 'P001';
        $expectedStatusCode0 = Response::HTTP_OK;

        return [
            [$requestContent0, $expectedStatusCode0]
        ];
    }

    /**
     * @dataProvider getTestUpdateProductActionDataProvider
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

    public function getTestUpdateProductActionDataProvider()
    {
        $requestContent0 = [
            'productCode' => 'P001',
            'quantity' => 12.0,
            'pricePerUnit' => 15.0,
            'stockAvialable' => 1
        ];
        $expectedStatusCode0 = Response::HTTP_OK;

        return [
            [$requestContent0, $expectedStatusCode0]
        ];
    }

    /**
     * @dataProvider getTestCreateProductActionDataProvider
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

    public function getTestCreateProductActionDataProvider()
    {
        $requestContent0 = [
            'productCode' => 'P031',
            'productName' => 'bottle',
            'productDescription' => 'Good Product',
            'quantity' => 12.0,
            'pricePerUnit' => 15.0,
            'stockAvialable' => 1,
            'unit' => 'piece',
            'status' => 1
        ];
        $expectedStatusCode0 = Response::HTTP_OK;

        return [
            [$requestContent0, $expectedStatusCode0]
        ];
    }
}
