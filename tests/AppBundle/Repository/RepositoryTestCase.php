<?php
namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Customer;

class RepositoryTestCase
{
    public function customerRepFindOneByIdTestCase() {
        $c1 = new Customer();
        $c1->setName('customer_test1')->setPhoneNumber('9777096808');

        $c2 = new Customer();
        $c2->setName('customer_test2')->setPhoneNumber('9777096809');

        return [
            [
                'phoneNumber' => $c1->getPhoneNumber(),
                'customer' => $c1,
            ],
            [
                'phoneNumber' => $c2->getPhoneNumber(),
                'customer' => $c2,
            ]
        ];
    }

    public function productRepfetchProductListTestCase() {
        // Making first test case
        $testData0['filter'] = [
            'productCode' => 'P002',
            'productName' => 'shoes',
            'productDescription' => 'A good shoes',
            'quantity' => 12.00,
            'stockAvialable' => 1
        ];
        $testData0['pagination'] = ['page' => 1, 'limit' => 2];

        return [
            $testData0
        ];
    }

    public function countProductRecordsTestCase() {
        // Making first test case
        $testData0['filter'] = [
            'productCode' => 'P002',
            'productName' => 'shoes',
            'productDescription' => 'A good shoes',
            'quantity' => 12.00,
            'stockAvialable' => 1
        ];

        return [
            $testData0
        ];
    }

    public function fetchProductForOrderTestCase() {
        // Making first test case
        $testData0['orderItem'] = [
            'P002' => [
                'productCode' => 'P002',
                'quantity' => 2
            ]
        ];

        return [
            $testData0
        ];
    }

    public function fetchProductForOrderInvalidQuantityTestCase() {
        // Making first test case
        $testData0['orderItem'] = [
            'P002' => [
                'productCode' => 'P002',
                'quantity' => 78
            ]
        ];

        return [
            $testData0
        ];
    }

    public function fetchProductForOrderInvalidProductCodeTestCase() {
        // Making first test case
        $testData0['orderItem'] = [
            'XXXXX' => [
                'productCode' => 'XXXXX',
                'quantity' => 78
            ]
        ];

        return [
            $testData0
        ];
    }

    public function lockProductTestCase(){
        // Making first test case
        $testData0['productIds'] = [
            2
        ];

        return [
            $testData0
        ];
    }
}