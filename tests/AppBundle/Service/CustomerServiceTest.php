<?php
namespace tests\PhpunitBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Customer;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Service\CustomerService;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CustomerServiceTest extends KernelTestCase
{
    /** @var CustomerRepository|PHPUnit_Framework_MockObject_MockObject */
    private $customerRepositoryMock;
    /** @var EntityManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $entityManagerInterfaceMock;
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

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->customerService = new CustomerService();
        $this->customerService->setServiceContainer($container->get('service_container'));
        $this->customerService->setEntityManager($this->entityManagerInterfaceMock);
        $this->customerService->setLogger($container->get('monolog.logger.exception'));
        $this->customerService->setTranslator($container->get('translator.default'));
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->customerRepositoryMock = null;
        $this->entityManagerInterfaceMock = null;
        $this->customerService = null;
    }

    public function testCreateOne()
    {
        $customer = new Customer();
        $customer->setName('Name 1');
        $customer->setPhoneNumber('9777096808');

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('persist')
            ->with($customer);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->customerService->createOne(['name' => 'Name 1', 'phoneNumber' => '9777096808']);
        $this->assertEquals($customer->getId(), $result);
        $this->assertEquals($customer->getName(), 'Name 1');
        $this->assertEquals($customer->getPhoneNumber(), '9777096808');
    }

    /**
     * @dataProvider getAllCustomerDataProvider
     */
    public function testGetAll($customers, $expected)
    {
        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($customers);

        $this->entityManagerInterfaceMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);

        $result = $this->customerService->getAll();
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider getOneCustomerDataProvider
     */
    public function testGetOne($id, $customer, $expected)
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

        $result = $this->customerService->getOne($id);
        $this->assertEquals($expected, $result);
    }

    public function testUpdateOneShouldThrowExceptionForInvalidCustomerId()
    {
        $id = 666;

        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn(null);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage(sprintf('Customer with id [%s] not found', $id));

        $this->customerService->updateOne(['name' => 'New Name 1', 'phoneNumber' => '9777097809'], $id);
    }

    /**
     * @dataProvider getUpdateOneCustomerDataProvider
     */
    public function testUpdateOne($id, $customer)
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

        $this->customerService->updateOne(['name' => 'New Name 1', 'phoneNumber' => '9777096809'], $id);

        $this->assertEquals($customer->getName(), 'New Name 1');
        $this->assertEquals($customer->getPhoneNumber(), '9777096809');
    }

    public function testDeleteOneShouldThrowExceptionForInvalidCustomerId()
    {
        $id = 666;

        $this->customerRepositoryMock
            ->expects($this->once())
            ->method('findOneById')
            ->with($id)
            ->willReturn(null);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->customerRepositoryMock);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage(sprintf('Customer with id [%s] not found', $id));

        $this->customerService->deleteOne($id);
    }

    /**
     * @dataProvider getRemoveOneCustomerDataProvider
     */
    public function testDeleteOne($id, $customer)
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
            ->method('remove')
            ->with($customer);

        $this->entityManagerInterfaceMock
            ->expects($this->once())
            ->method('flush');

        $this->customerService->deleteOne($id);
    }

    public function getAllCustomerDataProvider()
    {
        $c0 = [];
        $customers0 = [$c0];

        $c1 = new Customer();
        $c1->setId(1);
        $c1->setName('Name 1');
        $c1->setPhoneNumber('9777096808');
        $customers1 = [$c1];

        $c2 = new Customer();
        $c2->setId(2);
        $c2->setName('Name 2');
        $c2->setPhoneNumber('9348575256');
        $customers2 = [$c1, $c2];

        return [
            [$customers0, $customers0],
            [$customers1, $customers1],
            [$customers2, $customers2]
        ];
    }

    public function getOneCustomerDataProvider()
    {
        $id0 = 0;
        $c0 = null;
        $customers0 = $c0;

        $id1 = 1;
        $c1 = new Customer();
        $c1->setId($id1);
        $c1->setName('Name 1');
        $c1->setPhoneNumber('9777096808');
        $customers1 = $c1;

        return [
            [$id0, $customers0, $c0],
            [$id1, $customers1, $c1],
        ];
    }

    public function getUpdateOneCustomerDataProvider()
    {
        $customer = new Customer();
        $customer->setId(1);
        $customer->setName('Name 1');
        $customer->setPhoneNumber('9777096809');

        return [
            [1, $customer],
        ];
    }

    public function getRemoveOneCustomerDataProvider()
    {
        $customer = new Customer();
        $customer->setId(1);
        $customer->setName('Name 1');
        $customer->setPhoneNumber('9777096808');

        return [
            [1, $customer],
        ];
    }
}