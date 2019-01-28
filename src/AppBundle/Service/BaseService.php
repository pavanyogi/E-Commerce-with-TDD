<?php
/**
 *  BaseService for providing commonly used Symfony Services to other Custom Services of Application.
 *  This Service class should be extended as parent Service to the custom Application Service.
 *
 *  @category Service
 *  @author Prafulla Meher<prafulla.m@mindfiresolutions.com>
 */

namespace AppBundle\Service;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class BaseService
{
    /**
     * @var ContainerInterface | Object
     */
    protected $serviceContainer;

    /**
     * @var EntityManager | Object
     */
    protected $entityManager;

    /**
     * @var LoggerInterface | Object
     */
    protected $logger;

    /**
     * @var TranslatorInterface | Object
     */
    protected $translator;

    /**
     * @return ContainerInterface | Object
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @return EntityManager | Object
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return LoggerInterface | Object
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return TranslatorInterface | Object
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param ContainerInterface | Object $serviceContainer
     */
    public function setServiceContainer($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param EntityManager | Object $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param LoggerInterface | Object $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param TranslatorInterface | Object $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }
}