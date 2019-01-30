<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Constants\ErrorConstants;

class OrderController extends Controller
{
    /**
     * @Route("/book_order", name="book_order", methods={"POST", "OPTIONS"})
     */
    public function placeOrderAction(Request $request)
    {
        $logger = $this->container->get('monolog.logger.exception');
        // $response to be returned from API.
        $response = NULL;
        try {
            $content = json_decode($request->getContent(), true);

            // Validate the Place Order Request
            $content = $this->container
                ->get('app.order_service')
                ->validatePlaceOrderRequest($content);

            // Process the request and fetch the list of products
            $processResult = $this->container
                ->get('app.order_service')
                ->processPlaceOrderRequest($content);

            // Creating the final array response.
            $response = $this->container
                ->get('app.api_response_service')
                ->createOrderApiSuccessResponse('BookOrderResponse',
                    $processResult['message']['response']);
        } catch (\Exception $ex) {
            $logger->error(__FUNCTION__ . 'Function failed due to error : '.
                $ex->getMessage());
            // Throwing Internal Server Error Response In case of Unknown Errors.
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }

        return $response;
    }
}
