<?php
namespace Tests\AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseRepositoryTest extends KernelTestCase
{
    /** @var EntityManagerInterface */
    protected $entityManager;

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
}