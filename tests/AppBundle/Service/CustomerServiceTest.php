<?php
namespace Tests\AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Service\CustomerService;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CustomerServiceTest extends BaseServiceTest
{
    /** @var CustomerRepository|PHPUnit_Framework_MockObject_MockObject */
    private $customerRepositoryMock;
    /** @var CustomerService */
    private $customerService;

    protected function setUp()
    {
        parent::setUp();
        $this->customerRepositoryMock = $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManagerInterfaceMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerService = new CustomerService();
        $this->customerService->setServiceContainer($this->serviceContainerMock);
        $this->customerService->setEntityManager($this->entityManagerInterfaceMock);
        $this->customerService->setLogger($this->logger);
        $this->customerService->setTranslator($this->translator);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->customerRepositoryMock = null;
        $this->customerService = null;
    }

    /**
     * @dataProvider createCustomerDataProvider
     */
    public function testCreateCustomer($customerData, $customer, $expectedMessage)
    {
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('persist')
            ->with($customer);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->customerService->createCustomer($customerData);
        $this->assertEquals($expectedMessage, $result);
    }

    public function createCustomerDataProvider() {
        $serviceTest = new ServiceTestCase();
        $createCustomerTestCases = $serviceTest->createCustomerTestCase();

        return $createCustomerTestCases;
    }

    /**
     * @dataProvider getAllCustomerDataProvider
     */
    public function testGetAllCustomer($customers, $expected)
    {
        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($customers);

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);

        $result = $this->customerService->getAllCustomer();
        $this->assertEquals($expected, $result);
    }

    public function getAllCustomerDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $getAllCustomerTestCases = $serviceTest->getAllCustomerTestCase();

        return $getAllCustomerTestCases;
    }

    /**
     * @dataProvider getCustomerDetailDataProvider
     */
    public function testGetCustomerDetail($id, $customer, $expected)
    {
        $this->customerRepositoryMock
            ->expects($this->any())
            ->method('findOneById')
            ->with($id)
            ->willReturn($customer);

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);

        $result = $this->customerService->getCustomerDetail($id);
        $this->assertEquals($expected, $result);
    }

    public function getCustomerDetailDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $getCustomerDetailTestCases = $serviceTest->getCustomerDetailTestCase();

        return $getCustomerDetailTestCases;
    }

    /**
     * @dataProvider getCustomerDetailInvalidCustomerIdDataProvider
     */
    public function testGetCustomerDetailThrowExceptionForInvalidCustomerId($id, $customer)
    {
        $this->customerRepositoryMock
            ->expects($this->any())
            ->method('findOneById')
            ->with($id)
            ->willReturn($customer);

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);
        $this->expectException(UnprocessableEntityHttpException::class);
        $this->customerService->getCustomerDetail($id);
    }

    public function getCustomerDetailInvalidCustomerIdDataProvider() {
        $serviceTest = new ServiceTestCase();
        $getCustomerDetailInvalidCustomerIdTestCases = $serviceTest->getCustomerDetailInvalidCustomerIdTestCase();

        return $getCustomerDetailInvalidCustomerIdTestCases;
    }

    /**
     * @dataProvider updateCustomerInvalidCustomerIdDataProvider
     */
    public function testUpdateCustomerShouldThrowExceptionForInvalidCustomerId($id, $updateParameter, $customer)
    {
        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($customer);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->customerService->updateCustomerDetails($updateParameter, $id);
    }

    public function updateCustomerInvalidCustomerIdDataProvider() {
        $serviceTest = new ServiceTestCase();
        $updateCustomerInvalidCustomerIdTestCases = $serviceTest->updateCustomerInvalidCustomerIdTestCase();

        return $updateCustomerInvalidCustomerIdTestCases;
    }

    /**
     * @dataProvider updateCustomerDetailDataProvider
     */
    public function testUpdateOne($id, $updateParameter, $customer, $expectedMessage)
    {
        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn($customer);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);
        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->customerService->updateCustomerDetails($updateParameter, $id);
        $this->assertEquals($result, $expectedMessage);
    }

    public function updateCustomerDetailDataProvider()
    {
        $serviceTest = new ServiceTestCase();
        $updateCustomerDetailTestCases = $serviceTest->updateCustomerDetailTestCase();

        return $updateCustomerDetailTestCases;
    }
}