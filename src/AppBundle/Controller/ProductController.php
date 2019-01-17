<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Constants\ErrorConstants;

class ProductController extends Controller
{
    /**
     * @Route("/product/list", name="product_list", methods={"GET", "OPTIONS"})
     */
    public function getProductListAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        // $response to be returned from API.
        $response = NULL;
        try {
            $content = json_decode($request->getContent(), true);
            // Process the request and fetch the list of products
            $processResult = $this->container
                ->get('app.service.product')
                ->processFetchProductList($content);

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createProductsApiResponse('productListResponse',
                    $processResult['message']['response']);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }

    /**
     * @Route("/product/detail", name="product_detail", methods={"GET", "OPTIONS"})
     */
    public function getProductDetailAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        $content = json_decode(trim($request->getContent()), true);
        // $response to be returned from API.
        $response = NULL;
        try {
            // Process the request and fetch the list of products
            $processResult = $this->container
                ->get('app.service.product')
                ->getProductDetail($content['productCode']);

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createProductsApiResponse('productDetailResponse', $processResult['message']['response']);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }

    /**
     * @Route("/product/create", name="product_create", methods={"POST", "OPTIONS"})
     */
    public function createProductAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        $content = json_decode(trim($request->getContent()), true);
        // $response to be returned from API.
        $response = NULL;
        try {
            // Process the request and fetch the list of products
            $processProduct = $this->container
                ->get('app.service.product')
                ->createProduct($content);

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createProductsApiResponse('productCreatedResponse', $processProduct['message']['response']);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }

    /**
     * @Route("/product/update", name="product_update", methods={"PUT", "OPTIONS"})
     */
    public function updateProductAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        $content = json_decode(trim($request->getContent()), true);
        // $response to be returned from API.
        $response = NULL;
        try {
            // Process the request and fetch the list of products
            $processResult = $this->container
                ->get('app.service.product')
                ->updateProduct($content);

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createProductsApiResponse('productUpdateResponse', $processResult['message']['response']);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }
}
