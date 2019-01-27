<?php
namespace Tests\AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManager;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class BaseServiceTest extends KernelTestCase
{
    /** @var EntityManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    protected $entityManagerInterfaceMock;

    /** @var Container|PHPUnit_Framework_MockObject_MockObject */
    protected $serviceContainerMock;

    /** @var UserManager|PHPUnit_Framework_MockObject_MockObject */
    protected $userManagerMock;

    /** @var EncoderFactory|PHPUnit_Framework_MockObject_MockObject */
    protected $encoderFactoryMock;

    /** @var Translator */
    protected $translator;

    /** @var Logger */
    protected $logger;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->setServiceContainerMock()->setEntityManagerInterfaceMock()
            ->setUserManagerMock()->setEncodeFactoryMock()->setLogger()->setTranslator();
    }

    /**
     * @return $this
     */
    public function setEntityManagerInterfaceMock() {
        $this->entityManagerInterfaceMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $this;
    }

    /**
     * @return $this
     */
    public function setServiceContainerMock() {
       $this->serviceContainerMock = $this->getMockBuilder(Container::Class)
            ->disableOriginalConstructor()
            ->getMock();

       return $this;
    }

    /**
     * @return $this
     */
    public function setUserManagerMock() {
        $this->userManagerMock = $this->getMockBuilder(UserManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $this;
    }

    /**
     * @return $this
     */
    public function setEncodeFactoryMock() {
        $this->encoderFactoryMock = $this->getMockBuilder(EncoderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $this;
    }

    /**
     * @return $this
     */
    public function setTranslator() {
        $container = self::$kernel->getContainer();
        $this->translator = $container->get('translator.default');

        return $this;
    }

    /**
     * @return $this
     */
    public function setLogger() {
        $container = self::$kernel->getContainer();
        $this->logger = $container->get('monolog.logger.exception');

        return $this;
    }
}