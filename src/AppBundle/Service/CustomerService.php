<?php
namespace AppBundle\Service;

use AppBundle\Entity\Customer;
use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CustomerService extends BaseService
{
    public function getAllCustomer()
    {
        $processResult['status'] = false;
        try {
            $customers = $this->entityManager->getRepository(Customer::class)->findAll();
            $processResult['message']['response'] = [
                'customers' => $customers
            ];
            $processResult['status'] = true;
        } catch (\Exception $ex) {
            $this->logger->error('Get Customer could not be processed due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }

    public function getCustomerDetail($id)
    {
        $processResult['status'] = false;
        try {
            $customer = $this->entityManager->getRepository(Customer::class)->findOneById($id);
            if(!$customer) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_CUSTOMER_ID);
            }

            $processResult['message']['response'] = $customer;
            $processResult['status'] = true;
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error(__FUNCTION__.' Function failed due to Error :'. $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }

    public function createCustomer($customerData)
    {
        $processResult['status'] = false;
        try {
            $customer = new Customer();
            $customer->setName($customerData['name'])
                ->setPhoneNumber($customerData['phoneNumber']);

            $this->entityManager->persist($customer);
            $this->entityManager->flush();
            $processResult['status'] = true;
            $processResult['message']['response'] = $this->getTranslator()
                ->trans('api.response.success.customer_created');
        } catch (\Exception $ex) {
            $this->logger->error('Create Customer could not be processed due to Error : '.
            $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }

    public function updateCustomerDetails($customerData, $id)
    {
        $processResult['status'] = false;
        try {
            $customer = $this->entityManager->getRepository(Customer::class)->findOneById($id);
            if (!$customer) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_CUSTOMER_ID);
            }
            $customer->setName($customerData['name']);
            $customer->setPhoneNumber($customerData['phoneNumber']);

            $this->entityManager->flush();
            $processResult['status'] = true;
            $processResult['message']['response'] = $this->translator->trans('api.response.success.customer_updated');
        } catch (UnprocessableEntityHttpException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            $this->logger->error('Update Customer could not be processed due to Error : '.
            $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processResult;
    }
}