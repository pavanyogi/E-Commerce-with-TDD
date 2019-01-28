<?php
/**
 *  Service Class for Creating API Request Response.
 *
 *  @category Service
 *  @author Ashish Kumar
 */
namespace Tests\AppBundle\Service;

use PHPUnit_Framework_MockObject_MockObject;
use AppBundle\Service\OrderService;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\ProductRepository;
use AppBundle\Entity\OrderDetail;
use AppBundle\Entity\Orders;

class OrderServiceTest extends BaseServiceTest
{
    /** @var CustomerRepository|PHPUnit_Framework_MockObject_MockObject */
    private $customerRepositoryMock;
    /** @var ProductRepository|PHPUnit_Framework_MockObject_MockObject */
    private $productRepositoryMock;
    /** @var OrderService */
    private $orderService;

    protected function setUp()
    {
        parent::setUp();
        $this->customerRepositoryMock = $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productRepositoryMock = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderService = new OrderService();
        $this->orderService->setServiceContainer($this->serviceContainerMock);
        $this->orderService->setEntityManager($this->entityManagerInterfaceMock);
        $this->orderService->setLogger($this->logger);
        $this->orderService->setTranslator($this->translator);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->customerRepositoryMock = null;
        $this->productRepositoryMock = null;
        $this->orderService = null;
    }

    /**
     * @dataProvider getPlaceOrderDataProvider
     */
    public function testProcessPlaceOrderRequest($requestContent, $customer, $products)
    {
        $this->entityManagerInterfaceMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                $this->equalTo('AppBundle:Customer'),
                $this->equalTo('AppBundle:Product')
            ))
            ->will($this->returnCallback(
                function($entityName) use($requestContent, $products, $customer) {

                    if ($entityName === 'AppBundle:Customer') {
                        $this->customerRepositoryMock
                            ->expects($this->once())
                            ->method('findOneBy')
                            ->with(['phoneNumber' => $requestContent['customerDetails']['phoneNumber']])
                            ->willReturn($customer);

                        return $this->customerRepositoryMock;
                    }

                    if ($entityName === 'AppBundle:Product') {
                        $this->productRepositoryMock
                            ->expects($this->once())
                            ->method('fetchProductForOrder')
                            ->with($requestContent['orderItems'])
                            ->willReturn($products);

                        return $this->productRepositoryMock;
                    }
                }
            ));

        $this->entityManagerInterfaceMock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($this->getConnectionMock()));

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('lockProduct')
            ->with(array_column($products, 'id'));

        $this->entityManagerInterfaceMock
            ->expects($this->exactly(3))
            ->method('persist')
            ->withConsecutive([$this->isInstanceOf(Orders::class)],
                [$this->isInstanceOf(OrderDetail::class)],
                [$this->isInstanceOf(OrderDetail::class)]);

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('flush');

        $result = $this->orderService->processPlaceOrderRequest($requestContent);
        $this->assertNotNull($result);
    }

    public function getPlaceOrderDataProvider() {
        $serviceTest = new ServiceTestCase();
        $placeOrderTestCases = $serviceTest->getPlaceOrderTestCase();

        return $placeOrderTestCases;
    }

    /**
     * @dataProvider validatePlaceOrderDataProvider
     */
    public function testValidatePlaceOrderRequest($orderItemsInput, $orderItemsExpected)
    {
        $result = $this->orderService->validatePlaceOrderRequest($orderItemsInput);
        $this->assertNotNull($orderItemsExpected);
        $this->assertEquals($orderItemsExpected, $result);
    }

    public function validatePlaceOrderDataProvider() {
        $serviceTest = new ServiceTestCase();
        $validateplaceOrderRequestTestCases = $serviceTest->validatePlaceOrderRequestTestCase();

        return $validateplaceOrderRequestTestCases;
    }

    /**
     * @dataProvider fetchOrCreateCustomerDataProvider
     */
    public function testFetchOrCreateCustomer($customerDataInput, $customer, $expectedResult)
    {
        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['phoneNumber' => $customerDataInput['phoneNumber']])
            ->willReturn($customer);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);

        if(!$customer) {
            $this->entityManagerInterfaceMock
                ->expects($this->once())
                ->method('persist')
                ->with($expectedResult);

            $this->entityManagerInterfaceMock
                ->expects($this->once())
                ->method('flush');
        }

        $result = $this->orderService->fetchOrCreateCustomer($customerDataInput);
        $this->assertEquals($result, $expectedResult);
    }

    public function fetchOrCreateCustomerDataProvider() {
        $serviceTest = new ServiceTestCase();
        $fetchOrCreateCustomerTestCases = $serviceTest->fetchOrCreateCustomerTestCase();

        return $fetchOrCreateCustomerTestCases;
    }


    /**
     * @return \Doctrine\DBAL\Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getConnectionMock()
    {
        $mock = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'beginTransaction',
                    'commit',
                    'rollback',
                    'prepare',
                    'query',
                    'executeQuery',
                    'executeUpdate',
                    'getDatabasePlatform',
                )
            )
            ->getMock();
        return $mock;
    }
}
