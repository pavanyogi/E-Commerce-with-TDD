<?php
/**
 *  Service Class for Creating API Request Response.
 *
 *  @category Service
 *  @author Ashish Kumar
 */
namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Entity\Orders;
use AppBundle\Entity\OrderDetail;
use AppBundle\Entity\Customer;

class OrderService extends BaseService
{
    public function validatePlaceOrderRequest($content) {
        $orderItems = $content['orderItems'];
        $content['orderItems'] = [];
        foreach ($orderItems as $orderItem) {
            $content['orderItems'][$orderItem['productCode']] = $orderItem;
        }

        return $content;
    }

    public function processPlaceOrderRequest($requestContent)
    {
        $processingResult['status'] = false;
        try {
            // Get the Customer and Logged in user Object
            $customer = $this->fetchOrCreateCustomer($requestContent['customerDetails']);
            $loggedinUser = null;

            // fetch the product and lock it
            $this->entityManager->getConnection()->beginTransaction();
            $productRepo = $this->entityManager->getRepository('AppBundle:Product');
            $orderItems = $requestContent['orderItems'];
            $products = $productRepo->fetchProductForOrder($orderItems);
            $productRepo->lockProduct(array_column($products, 'id'));
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();

            // Generate the OrderId
            $orderId = $this->generateOrderId();
            $orders = new Orders();
            $orders->setAgentId($loggedinUser)->setCustomerId($customer)
                ->setBookedDate(new \DateTime("now"))->setOrderId($orderId);
            $this->entityManager->persist($orders);
            foreach ($products as $productDetail) {
                $product = $productRepo->find($productDetail['id']);
                $orderDetail = new OrderDetail();
                $orderDetail->setOrderId($orders)->setProductId($product)
                    ->setQuantity($productDetail['quantity'])->setPrice($productDetail['pricePerUnit']);
                $this->entityManager->persist($orderDetail);
            }
            $this->entityManager->flush();

            $processingResult['status'] = true;
            $processingResult['message']['response'] = $orders->getOrderId();
        } catch (\Exception $ex) {
            print_r($ex->getMessage()); die();
            $this->logger->error('OAuth Request could not be processed due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $processingResult;
    }

    public function generateOrderId()
    {
        $microtime = microtime(true);
        $orderId = str_replace('.', '', $microtime);
        $orderId = strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4 )).
            mt_rand(100,500).
            substr(str_shuffle($orderId), 0, 4).
            mt_rand(501,999);

        return $orderId;
    }

    public function fetchOrCreateCustomer($customerDetails) {
        try {
            $customer = $this->entityManager->getRepository('AppBundle:Customer')
                ->findOneBy(['phoneNumber' => $customerDetails['phoneNumber']]);

            if (!$customer) {
                $customer = new Customer();
                $customer->setPhoneNumber($customerDetails['phoneNumber']);
                $customer->setName($customerDetails['name']);
                $this->entityManager->persist($customer);
                $this->entityManager->flush();
            }

        } catch (\Exception $ex) {
            print_r($ex->getMessage()); die();
            $this->logger->error('Fetch/Create Customer could not be processed due to Error : '.
                $ex->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $customer;
    }
}
