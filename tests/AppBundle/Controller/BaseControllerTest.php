<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Kernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class BaseControllerTest extends WebTestCase
{
    /**
     * @var WebTestCase
     */
    protected $client;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->client = static::createClient();
        $this->container = self::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->client = null;
        $this->container = null;
        $this->entityManager = null;
    }
}
