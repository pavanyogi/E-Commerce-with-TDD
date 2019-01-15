<?php
namespace AppBundle\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CustomerService
{
    /*private $customerRepository;*/
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        return $this->entityManager->getRepository(Customer::class)->findAll();
    }

    public function getOne($id)
    {
        return $this->entityManager->getRepository(Customer::class)->findOneById($id);
    }

    public function createOne(array $payload)
    {
        $customer = new Customer();
        $customer->setName($payload['name']);
        $customer->setPhoneNumber($payload['phoneNumber']);

        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        return $customer->getId();
    }

    public function updateOne(array $payload, $id)
    {
        $customer = $this->entityManager->getRepository(Customer::class)->findOneById($id);
        if (!$customer instanceof Customer) {
            throw new BadRequestHttpException(sprintf('Customer with id [%s] not found', $id));
        }

        $customer->setName($payload['name']);
        $customer->setPhoneNumber($payload['phoneNumber']);

        $this->entityManager->flush();
    }

    public function deleteOne($id)
    {
        $customer = $this->entityManager->getRepository(Customer::class)->findOneById($id);
        if (!$customer instanceof Customer) {
            throw new BadRequestHttpException(sprintf('Customer with id [%s] not found', $id));
        }

        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }
}