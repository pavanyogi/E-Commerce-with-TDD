<?php
/**
 *  Service Class for Creating API Request Response.
 *
 *  @category Service
 *  @author Ashish Kumar
 */
namespace tests\AppBundle\Service;

use AppBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use AppBundle\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\ProductRepository;
use AppBundle\Entity\OrderDetail;
use AppBundle\Entity\Orders;

class OrderServiceTest extends KernelTestCase
{
    /** @var EntityManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $entityManagerInterfaceMock;
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

        $this->entityManagerInterfaceMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productRepositoryMock = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->orderService = new OrderService();
        $this->orderService->setServiceContainer($container->get('service_container'));
        $this->orderService->setEntityManager($this->entityManagerInterfaceMock);
        $this->orderService->setLogger($container->get('monolog.logger.exception'));
        $this->orderService->setTranslator($container->get('translator.default'));
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManagerInterfaceMock = null;
        $this->orderService = null;
    }

    /**
     * @dataProvider getPlaceOrderDataProvider
     */
    public function testProcessPlaceOrderRequest($requestContent)
    {
        $customer = new Customer();
        $customer->setName($requestContent['customerDetails']['name'])
            ->setPhoneNumber($requestContent['customerDetails']['phoneNumber']);

        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['phoneNumber' => $requestContent['customerDetails']['phoneNumber']])
            ->willReturn($customer);

        $fetchProductForOrderReturnedContent = [
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

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('fetchProductForOrder')
            ->with($requestContent['orderItems'])
            ->willReturn($fetchProductForOrderReturnedContent);

        $this->entityManagerInterfaceMock->expects($this->any())
            ->method('getRepository')
            ->with($this->anything())
            ->will($this->returnCallback(
                function($entityName) use($requestContent, $fetchProductForOrderReturnedContent, $customer) {

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
                            ->willReturn($fetchProductForOrderReturnedContent);

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
            ->with(array_column($fetchProductForOrderReturnedContent, 'id'));

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('flush');

        $orders = new Orders();
        $orders->setAgentId(null);
        $orders->setOrderId($this->orderService->generateOrderId());
        $orders->setBookedDate(new \DateTime('now'));
        $orders->setCustomerId($customer);

        $orderDetail1 = new OrderDetail();
        $orderDetail1->setPrice(23)
            ->setQuantity(23)
            ->setProductId(1)
            ->setOrderId($orders);

        $orderDetail2 = new OrderDetail();
        $orderDetail2->setPrice(23)
            ->setQuantity(23)
            ->setProductId(2)
            ->setOrderId($orders);

        $result = $this->orderService->processPlaceOrderRequest($requestContent);
        $this->assertNotNull($result);
    }

    public function getPlaceOrderDataProvider() {
        $requestContent = [];
        $requestContent['orderItems'] = [
            'P001' => [
                'productCode' => 'P001',
                'quantity' => 2
            ],
            'P002' => [
                'productCode' => 'P002',
                'quantity' => 2
            ]
        ];
        $requestContent['customerDetails'] = [
            'name' => 'Prafulla Meher',
            'phoneNumber' => '9777096808'
        ];

        return [
            [$requestContent]
        ];
    }

    /**
     * @dataProvider getValidatePlaceOrderDataProvider
     */
    public function testValidatePlaceOrderRequest($orderItemsInput, $orderItemsExpected)
    {
        $result = $this->orderService->validatePlaceOrderRequest($orderItemsInput);
        $this->assertNotNull($orderItemsExpected);
        $this->assertEquals($orderItemsExpected, $result);
    }

    public function getValidatePlaceOrderDataProvider() {
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
        $orderItemsExpected['customerDetails'] = [
            'name' => 'Prafulla Meher',
            'phoneNumber' => '9777096808',
            'address' => 'Bhubaneswar',
        ];

        return [
            [$orderItemsInput, $orderItemsExpected]
        ];
    }

    /**
     * @dataProvider getFetchOrCreateCustomerDataProvider
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

    public function getFetchOrCreateCustomerDataProvider() {
        $customerDataInput = [
            'name' => 'Prafulla Meher',
            'phoneNumber' => '9777096808',
            'address' => 'Bhubaneswar',
        ];

        $customer = new Customer();
        $customer->setPhoneNumber($customerDataInput['phoneNumber']);
        $customer->setName($customerDataInput['name']);
        $expectedResult = $customer;

        return [
            [$customerDataInput, null, $expectedResult],
            [$customerDataInput, $customer, $expectedResult]
        ];
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
