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
            // Process the request and fetch the list of products
            $products = $this->container
                ->get('app.service.product')
                ->getAll();

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createGetProductsApiResponse($products);
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

    }

    /**
     * @Route("/product/create", name="product_create", methods={"POST", "OPTIONS"})
     */
    public function createProductAction(Request $request)
    {

    }

    /**
     * @Route("/product/update", name="product_update", methods={"PUT", "OPTIONS"})
     */
    public function updateProductAction(Request $request)
    {

    }
}
