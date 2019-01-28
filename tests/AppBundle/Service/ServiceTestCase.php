<?php
/**
 *  ServiceTestCase Class for providing the test case to service class.
 *
 *  @category ServiceTestCase
 *  @author Prafulla Meher
 */
namespace Tests\AppBundle\Service;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Product;
use Symfony\Component\PropertyAccess\PropertyAccess;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class ServiceTestCase
{
    public function getUserTestCase() {
        // Making the testcase0
        $testData0['inputData'] = [
            'username' => 'testUser',
            'usernameCanonical' => 'testUser',
            'email' => 'test@gmail.com',
            'enabled' => 1,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com'
        ];
        $testData0['user'] = $this->createObjectFromArray($testData0['inputData'], User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function getInvalidCredentialUserTestCase() {
        // Making the testcase0
        $inputData0 = [
            'username' => 'testUser',
            'usernameCanonical' => 'testUser',
            'email' => 'test@gmail.com',
            'enabled' => 1,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com'
        ];
        $testData0['credentials'] = [
            'username' => 'testUser',
            'password' => '123',
        ];
        $testData0['user'] = $this->createObjectFromArray($inputData0, User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function getValidateUserCredentialsTestCase() {
        // Making the testcase0
        $inputData0 = [
            'username' => 'testUser',
            'usernameCanonical' => 'testUser',
            'email' => 'test@gmail.com',
            'enabled' => 1,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com'
        ];
        $testData0['credentials'] = [
            'username' => 'testUser',
            'password' => '123',
        ];
        $testData0['user'] = $this->createObjectFromArray($inputData0, User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function getValidateUserCredentialsForInvalidUserNameTestCase() {
        // Making the testcase0
        $testData0['credentials'] = [
            'username' => 'testUser',
            'password' => '123',
        ];
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function getDisabledUserTestCase() {
        // Making the testcase0
        $inputData0 = [
            'username' => 'testUser',
            'usernameCanonical' => 'testUser',
            'email' => 'test@gmail.com',
            'enabled' => 0,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com'
        ];
        $testData0['credentials'] = [
            'username' => 'testUser',
            'password' => '123',
        ];
        $testData0['user'] = $this->createObjectFromArray($inputData0, User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function createAuthenticationTokenForUserTestCase() {
        // Making the testcase0
        $inputData0 = [
            'username' => 'testUser',
            'usernameCanonical' => 'testUser',
            'email' => 'test@gmail.com',
            'enabled' => 1,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com'
        ];
        $testData0['user'] = $this->createObjectFromArray($inputData0, User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function authenticateApiRequestTestCase() {
        // Making the testcase0
        $inputData0 = [
            'username' => 'testAuthenticationAuthorization',
            'usernameCanonical' => 'testAuthenticationAuthorization',
            'email' => 'testAuthenticationAuthorization@gmail.com',
            'enabled' => 1,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com',
            'authenticationToken' => 'ABCDED@#$RETRY'
        ];
        $testData0['headerData'] = [
            'Content-Type' => 'application/json',
            'Authorization' => $inputData0['authenticationToken'],
            'username' => $inputData0['username']
        ];
        $testData0['user'] = $this->createObjectFromArray($inputData0, User::class, null);
        $testData0['expectedResult'] = [
            'status' => 1,
            'message' => [
                'username' => 'testAuthenticationAuthorization'
            ]
        ];
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function authenticateApiRequestInvalidContentTypeTestCase() {
        // Making the testcase0
        $testData0['headerData'] = [
            'Content-Type' => 'application/xml'
        ];
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function authenticateApiRequestInvalidAuthorizationTestCase() {
        // Making the testcase0
        $testData0['headerData'] = [
            'Content-Type' => 'application/json',
            'Authorization' => '123'
        ];
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function authenticateApiRequestInvalidUserTestCase() {
        // Making the testcase0
        $testData0['headerData'] = [
            'Content-Type' => 'application/json',
            'Authorization' => 'ABCDFGRTEDRTFY',
            'username' => '******'
        ];
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function authenticateApiRequestDisabledUserTestCase() {
        // Making the testcase0
        $inputData0 = [
            'username' => 'testAuthenticationAuthorization',
            'usernameCanonical' => 'testAuthenticationAuthorization',
            'email' => 'testAuthenticationAuthorization@gmail.com',
            'enabled' => 0,
            'password' => 'b671b9be4bc2ee60ee7f61ad19c06e5203488b69',
            'salt' => 'DQva6c/qhcN/YEkQG4.ynx4AFbf.5MhfsgGEKZiNi7k',
            'emailCanonical' => 'test@gmail.com',
            'authenticationToken' => 'ABCDED@#$RETRY'
        ];
        $testData0['headerData'] = [
            'Content-Type' => 'application/json',
            'Authorization' => $inputData0['authenticationToken'],
            'username' => $inputData0['username']
        ];
        $testData0['user'] = $this->createObjectFromArray($inputData0, User::class, null);
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function fetchProductListTestCase() {
        // Making request Content to be sent
        $requestContent['filter'] = [];
        $requestContent['pagination'] = [];

        // Making first test case
        $p0 = [];
        $product0 = $p0;
        $expectedResult0['status'] = true;
        $expectedResult0['message']['response'] = [
            'products' => $product0,
            'count' => count($product0)
        ];

        // Making 2nd Test case
        $p1 = [
            'productCode' => 'P001',
            'productName' => 'shoes',
            'productDescription' => 'Good shoes',
            'quantity' => 12.0,
            'pricePerunit' => 12.0,
            'status' => 1,
            'stockAvialable' => 1,
            'unit' => 'pair'
        ];
        $product1 = [$p1];
        $expectedResult1['status'] = true;
        $expectedResult1['message']['response'] = [
            'products' => $product1,
            'count' => count($product1)
        ];

        // Making third test case
        $p2 = [
            'productCode' => 'P002',
            'productName' => 'shirt',
            'productDescription' => 'Good shirt',
            'quantity' => 12.0,
            'pricePerunit' => 12.0,
            'status' => 1,
            'stockAvialable' => 1,
            'unit' => 'piece'
        ];
        $product2 = [$p1, $p2];
        $expectedResult2['status'] = true;
        $expectedResult2['message']['response'] = [
            'products' => $product2,
            'count' => count($product2)
        ];

        return [
            [$requestContent, $product0, $expectedResult0],
            [$requestContent, $product1, $expectedResult1],
            [$requestContent, $product2, $expectedResult2]
        ];
    }

    public function getProductDetailTestCase() {
        // Making the first testcase
        $productData0 = [
            'productCode' => 'P001',
            'productName' => 'shoes',
            'productDescription' => 'Good shoes',
            'quantity' => 12.0,
            'pricePerUnit' => 12.0,
            'stockAvialable' => 1,
            'unit' => 'pair',
            'status' => 1
        ];
        $testData0['productCode'] = 'P001';
        $testData0['product'] = $this->createObjectFromArray($productData0, Product::class, null);
        $testData0['expectedResult'] = [
            'status' => true,
            'message' => [
                'response' => [
                    'productCode' => $productData0['productCode'],
                    'productName' => $productData0['productName'],
                    'quantity' => $productData0['quantity'],
                    'pricePerUnit' => $productData0['pricePerUnit'],
                    'stockAvialable' => $productData0['stockAvialable']
                ]
            ]
        ];

        // Making the array of testcases and returning it
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function createProductTestCase() {
        // Making the first testcase
        $testData0['createProductData'] = [
            'productCode' => 'P001',
            'productName' => 'shoes',
            'productDescription' => 'Good shoes',
            'quantity' => 12.0,
            'pricePerUnit' => 12.0,
            'stockAvialable' => 1,
            'unit' => 'pair',
            'status' => 1
        ];
        $testData0['product'] = $this->createObjectFromArray($testData0['createProductData'],
            Product::class, null);
        $testData0['expectedResult'] = [
            'status' => true,
            'message' => [
                'response' => 'Product Created Successfully.'
            ]
        ];

        // Making the array of testcases and returning it
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function getProductDetailInvaidProductCodeTestCase() {
        return [
            ['XXXX']
        ];
    }

    public function updateProductDetailInvaidProductCodeTestCase() {
        // Making first Test case
        $testData0['updateParameter'] = [
            'productCode' => 'P001',
            'quantity' => 12.0,
            'pricePerUnit' => 12.0,
            'stockAvialable' => 0
        ];

        // Making the array of testcases and returning it
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function updateProductTestCase() {
        // Making first Test case
        $productEntityData0 = [
            'productCode' => 'P001',
            'productName' => 'shoes',
            'productDescription' => 'Good shoes',
            'unit' => 'pair',
            'quantity' => 12.0,
            'pricePerUnit' => 12.0,
            'stockAvialable' => 0
        ];
        $testData0['updateParameter'] = [
            'productCode' => 'P001',
            'quantity' => 12.0,
            'pricePerUnit' => 12.0,
            'stockAvialable' => 0
        ];
        $testData0['product'] = $this->createObjectFromArray($productEntityData0, Product::class, null);
        $testData0['expextedResult'] = [
            'status' => true,
            'message' => [
                'response' => 'Product Updated Successfully.'
            ]
        ];

        // Making the array of testcases and returning it
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function createCustomerTestCase() {
        // Making first Test case
        $testData0['customerData'] = [
            'name' => 'Test Name',
            'phoneNumber' => '9777096808'
        ];
        $testData0['customer'] = $this->createObjectFromArray($testData0['customerData'],
            Customer::class, null);
        $testData0['expextedResult'] = [
            'status' => true,
            'message' => [
                'response' => 'Customer Created Successfully.'
            ]
        ];

        // Making the array of testcases and returning it
        $testCases = [
            $testData0
        ];

        return $testCases;
    }

    public function getAllCustomerTestCase() {
        $c0 = [];
        $customers0 = [$c0];
        $expectedResult0 = [
            'status' => true,
            'message' => [
                'response' => [
                    'customers' => $customers0
                ]
            ]
        ];

        $c1 = $this->createObjectFromArray(['name' => 'Test Name 1', 'phoneNumber' => '9777096808'],
            Customer::class, null);
        $customers1 = [$c1];
        $expectedResult1 = [
            'status' => true,
            'message' => [
                'response' => [
                    'customers' => $customers1
                ]
            ]
        ];

        $c2 = $this->createObjectFromArray(['name' => 'Test Name 2', 'phoneNumber' => '9348575256'],
            Customer::class, null);
        $customers2 = [$c1, $c2];
        $expectedResult2 = [
            'status' => true,
            'message' => [
                'response' => [
                    'customers' => $customers2
                ]
            ]
        ];

        return [
            [$customers0, $expectedResult0],
            [$customers1, $expectedResult1],
            [$customers2, $expectedResult2]
        ];
    }

    public function getCustomerDetailTestCase() {
        // Making 1st Test case
        $id0 = 1;
        $customers0 = $this->createObjectFromArray(['name' => 'Test Name', 'phoneNumber' => '9777096808'],
            Customer::class, null);
        $expectedResult0 = [
            'status' => true,
            'message' => [
                'response' => $customers0
            ]
        ];

        // Making array of testcase and returning it
        return [
            [$id0, $customers0, $expectedResult0],
        ];
    }

    public function getCustomerDetailInvalidCustomerIdTestCase() {
        // Making first Test case
        $id0 = 0;
        $customers0 = null;

        // Making array of testcase and returning it
        return [
            [$id0, $customers0]
        ];
    }

    public function updateCustomerInvalidCustomerIdTestCase() {
        // Making first Test case
        $id0 = 0;
        $updateParameter0 = [
            'name' => 'Test Name',
            'phoneNumber' => '9777097809'
        ];
        $customers0 = null;

        // Making array of testcase and returning it
        return [
            [$id0, $updateParameter0, $customers0]
        ];
    }

    public function updateCustomerDetailTestCase() {
        // Making first Test case
        $id0 = 0;
        $updateParameter0 = [
            'name' => 'Test Name',
            'phoneNumber' => '9777096809'
        ];
        $customers0 = $this->createObjectFromArray($updateParameter0,Customer::class, null);
        $expectedMessage0 = [
            'status' => true,
            'message' => [
                'response' => 'Customer Updated Successfully.'
            ]
        ];

        // Making array of testcase and returning it
        return [
            [$id0, $updateParameter0, $customers0, $expectedMessage0]
        ];
    }

    public function getPlaceOrderTestCase() {
        // Making first test case
        $requestContent0 = [];
        // Here pass only two products, If you are changing number of products then you should also
        // change the arguments passed to persist method also.
        $requestContent0['orderItems'] = [
            'P001' => [
                'productCode' => 'P001',
                'quantity' => 2
            ],
            'P002' => [
                'productCode' => 'P002',
                'quantity' => 2
            ]
        ];
        $requestContent0['customerDetails'] = [
            'name' => 'Prafulla Meher',
            'phoneNumber' => '9777096808'
        ];

        $customer0 = $this->createObjectFromArray($requestContent0['customerDetails'],
            Customer::class, null);

        $products0 = [
            [
                'id' => 1,
                'pricePerUnit' => 23,
                'quantity' => 23,
                'productCode' => 'P001'
            ],
            [
                'id' => 2,
                'pricePerUnit' => 23,
                'quantity' => 23,
                'productCode' => 'P002'
            ]
        ];

        // Making array of testcases and returning it
        return [
            [$requestContent0, $customer0, $products0]
        ];
    }

    public function validatePlaceOrderRequestTestCase() {
        $orderItemsInput = [];
        $orderItemsInput['orderItems'] = [
            [
                'productCode' => 'P001',
                'quantity' => 2
            ],
            [
                'productCode' => 'P002',
                'quantity' => 2
            ]
        ];
        $orderItemsInput['customerDetails'] = [
            'name' => 'Prafulla Meher',
            'phoneNumber' => '9777096808',
            'address' => 'Bhubaneswar',
        ];

        $orderItemsExpected = [];
        $orderItemsExpected['orderItems'] = [
            'P001' => [
                'productCode' => 'P001',
                'quantity' => 2
            ],
            'P002' => [
                'productCode' => 'P002',
                'quantity' => 2
            ]
        ];
        $orderItemsExpected['customerDetails'] = $orderItemsInput['customerDetails'];

        return [
            [$orderItemsInput, $orderItemsExpected]
        ];
    }

    public function fetchOrCreateCustomerTestCase() {
        $customerDataInput = [
            'name' => 'Prafulla Meher',
            'phoneNumber' => '9777096808',
            'address' => 'Bhubaneswar',
        ];

        $customer = new Customer();
        $customer->setPhoneNumber($customerDataInput['phoneNumber']);
        $customer->setName($customerDataInput['name']);
        $expectedResult = $customer;

        // Making array of testcases and returning it.
        return [
            [$customerDataInput, null, $expectedResult],
            [$customerDataInput, $customer, $expectedResult]
        ];
    }

    /**
     *  Function to fill Array data into the Class Object.
     *
     *  @param array $data
     *  @param string $className
     *  @param object $object
     *
     *  @return object
     *  @throws \Exception
     */
    public function createObjectFromArray($data, $className, $object)
    {
        try {
            // Checking the Object and Class Name Provided
            if (!empty($object) && !$object instanceof $className) {
                throw new \Exception('Invalid parameters provided to function ' . __FUNCTION__);
            }

            if (empty($object)) {
                $resourceClass = new \ReflectionClass($className);
                if (!$resourceClass->isInstantiable()) {
                    throw new \Exception($className . ' class name passed to function ' . __FUNCTION__ .
                        ' is not instantiable');
                }

                $object = $resourceClass->newInstance();
            }

            $propertyAccessor = PropertyAccess::createPropertyAccessor();

            // filling Array data to Object.
            // Note: All the properties of the class should be should be available
            // to be set by Setters.
            foreach ($data as $attribute => $value) {
                if (!$propertyAccessor->isWritable($object, $attribute)) {
                    throw new \Exception('Invalid array data provided to function ' . __FUNCTION__);
                }
                $propertyAccessor->setValue($object, $attribute, $value);
            }

            return $object;
        } catch (\Exception $ex) {

        }
    }
}