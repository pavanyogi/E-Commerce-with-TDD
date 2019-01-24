<?php
namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTime;

class CustomerRepositoryTest extends KernelTestCase
{
    /** @var EntityManagerInterface */
    private $entityManager;

    protected function setUp()
    {
        parent::setUp();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * @test
     * @dataProvider findAllRecordsDataProvider
     */
    public function findAllRecordsTest(int $id, string $name)
    {
        $customers = $this->entityManager
            ->getRepository(Customer::class)
            ->findAll();

        $this->assertSame($id, $customers[$id-1]['id']);
        $this->assertSame($name, $customers[$id-1]['name']);
    }

    public function findAllRecordsDataProvider()
    {
        return [
            [
                '$id' => 1,
                '$name' => 'prafulla_test1',
            ],
            [
                '$id' => 2,
                '$name' => 'prafulla_test2',
            ],
            [
                '$id' => 3,
                '$name' => 'prafulla_test3',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider findOneByIdDataProvider
     */
    public function findOneByIdTest($id, $customer)
    {
        $result = $this->entityManager->getRepository(Customer::class)->findOneById($id);
        $this->assertEquals($customer, $result);
    }

    public function findOneByIdDataProvider()
    {
        $c1 = new Customer();
        $c1->setId(1);
        $c1->setName('prafulla_test1');
        $c1->setPhoneNumber('9777096808');

        $c2 = new Customer();
        $c2->setId(2);
        $c2->setName('prafulla_test2');
        $c2->setPhoneNumber('9777096808');

        return [
            [
                '$id' => 1,
                '$customer' => $c1,
            ],
            [
                '$id' => 2,
                '$customer' => $c2,
            ]
        ];
    }
}