<?php
/**
 *  ServiceTestCase Class for providing the test case to service class.
 *
 *  @category ServiceTestCase
 *  @author Prafulla Meher
 */
namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class ControllerTestCase
{
    public function getLoginTestCases() {
        // Making first testcase
        $testData0['requestContent'] = [
            'credentials' => [
                'username' => 'superadmin',
                'password' => '123'
            ]
        ];

        $testData0['expectedStatusCode'] = Response::HTTP_OK;

        // Making array of test cases and returning it
        return [
            $testData0
        ];
    }

    public function getPlaceOrderActionTestCases() {
        // Making first testcases.
        $testData0['requestContent'] = [
            'orderItems' => [[
                'productCode' => 'P001',
                'quantity' => 2
            ]],
            'customerDetails' => [
                'name' => 'Prafulla Meher',
                'phoneNumber' => '9777096808'
            ]
        ];
        $testData0['expectedStatusCode'] = Response::HTTP_OK;

        // Making array of testcases and returning it.
        return [
            $testData0
        ];
    }

    public function getProductListActionTestCases() {
        // Making first test case
        $testData0['requestContent']['filter'] = [];
        $testData0['requestContent']['pagination'] = ['page' => 1, 'limit' => 2];
        $testData0['expectedStatusCode'] = Response::HTTP_OK;

        // Making second test case
        $testData1['requestContent']['filter'] = [
            'productCode' => 'P001',
            'productName' => 'shoes',
            'productDescription' => 'A good shoes',
            'quantity' => 12.00,
            'stockAvialable' => 1
        ];
        $testData1['requestContent']['pagination'] = ['page' => 1, 'limit' => 2];
        $testData1['expectedStatusCode'] = Response::HTTP_OK;

        // Making array of test cases and returning it
        return [
            $testData0,
            $testData1
        ];
    }

    public function getProductDetailActionTestCases() {
        $testData0['requestContent']['productCode'] = 'P001';
        $testData0['expectedStatusCode'] = Response::HTTP_OK;

        return [
            $testData0
        ];
    }

    public function updateProductActionTestCases() {
        $testData0['requestContent'] = [
            'productCode' => 'P001',
            'quantity' => 12.0,
            'pricePerUnit' => 15.0,
            'stockAvialable' => 1
        ];
        $testData0['expectedStatusCode'] = Response::HTTP_OK;

        return [
            $testData0
        ];
    }

    public function createProductActionTestCases() {
        $testData0['requestContent'] = [
            'productCode' => 'P031',
            'productName' => 'bottle',
            'productDescription' => 'Good Product',
            'quantity' => 12.0,
            'pricePerUnit' => 15.0,
            'stockAvialable' => 1,
            'unit' => 'piece',
            'status' => 1
        ];
        $testData0['expectedStatusCode'] = Response::HTTP_OK;

        return [
            $testData0
        ];
    }

    public function getCustomerDetailActionTestCases() {
        $requestContent = [
            'phoneNumber' => '9777096808'
        ];

        return [
            [$requestContent]
        ];
    }

    public function createCustomerActionTestCases() {
        $requestContent = [
            'name' => 'Prafulla',
            'phoneNumber' => '9777096808'
        ];

        return [
            [$requestContent]
        ];
    }
}