<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Constants\ErrorConstants;

class CustomerController extends Controller
{
    /**
     * @Route("/customer/list", name="customer_list", methods={"GET", "OPTIONS"})
     */
    public function getCustomerListAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        // $response to be returned from API.
        $response = NULL;
        try {
            // Process the request and fetch the list of products
            $customers = $this->container
                ->get('app.service.customer')
                ->getAll();

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createCustomerApiSuccessResponse('customerListResponse',
                    $customers);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }

    /**
     * @Route("/customer/detail", name="customer_detail", methods={"GET", "OPTIONS"})
     */
    public function getCustomerDetailAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        // $response to be returned from API.
        $response = NULL;
        try {
            $content = json_decode($request->getContent(), true);
            // Process the request and fetch the list of products
            $customers = $this->container
                ->get('app.service.customer')
                ->getOne($content['id']);

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createCustomerApiSuccessResponse('customerDetailResponse',
                    $customers);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }

    /**
     * @Route("/customer/create", name="customer_create", methods={"POST", "OPTIONS"})
     */
    public function createCustomerAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        $content = json_decode(trim($request->getContent()), true);
        // $response to be returned from API.
        $response = NULL;
        try {
            // Process the request and fetch the list of products
            $processProduct = $this->container
                ->get('app.service.customer')
                ->createOne($content);

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createCustomerApiSuccessResponse('productCreatedResponse', $processProduct);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }
}
