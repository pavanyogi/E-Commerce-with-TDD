<?php
namespace AppBundle\Service;

use AppBundle\Entity\Customer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CustomerService extends BaseService
{
    public function getAll()
    {
        try {
            return $this->entityManager->getRepository(Customer::class)->findAll();
        } catch (\Exception $ex) {
            $this->logger->error('Get Customer could not be processed due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    public function getOne($id)
    {
        try {
            return $this->entityManager->getRepository(Customer::class)->findOneById($id);
        } catch (\Exception $ex) {
            $this->logger->error('Get single Customer Data function could not be processed due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    public function createOne(array $customerData)
    {
        try {
            $customer = new Customer();
            $customer->setName($customerData['name']);
            $customer->setPhoneNumber($customerData['phoneNumber']);

            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        } catch (\Exception $ex) {
            $this->logger->error('Fetch/Create Customer could not be processed due to Error : '.
            $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $customer->getId();
    }

    public function updateOne(array $customerData, $id)
    {
        try {
            $customer = $this->entityManager->getRepository(Customer::class)->findOneById($id);
            if (!$customer instanceof Customer) {
                throw new UnprocessableEntityHttpException(sprintf('Customer with id [%s] not found', $id));
            }

            $customer->setName($customerData['name']);
            $customer->setPhoneNumber($customerData['phoneNumber']);

            $this->entityManager->flush();

        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error('Update Customer could not be processed due to Error : '.
            $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    public function deleteOne($id)
    {
        try {
            $customer = $this->entityManager->getRepository(Customer::class)->findOneById($id);
            if (!$customer instanceof Customer) {
                throw new UnprocessableEntityHttpException(sprintf('Customer with id [%s] not found', $id));
            }

            $this->entityManager->remove($customer);
            $this->entityManager->flush();

        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error('Update Customer could not be processed due to Error : '.
            $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}