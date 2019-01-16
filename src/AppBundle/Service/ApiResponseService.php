<?php
/**
 *  Service Class for Creating API Request Response.
 *
 *  @category Service
 *  @author Ashish Kumar
 */
namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;

class ApiResponseService
{
    /**
     *  Function to create API Error Response.
     *
     *  @param string $errorCode
     *  @param string $transactionId (default = null)
     *
     *  @return array
     */
    public function createApiErrorResponse($errorCode, $transactionId = null)
    {
        $response = [
            'Response' => [
                'reasonCode' => '1',
                'reasonText' => $this->translator->trans('api.response.failure.message'),
                'error' => [
                    'code' => ErrorConstants::$errorCodeMap[$errorCode]['code'],
                    'text' => $this->translator
                        ->trans(ErrorConstants::$errorCodeMap[$errorCode]['message'])
                ],
            ]
        ];

        if (!empty($transactionId)) {
            $response['transactionId'] = $transactionId;
        }
        return $response;
    }

    /**
     *  Function to create response of GET products API.
     *
     *  @param array $requestContent
     *  @param array $products
     *
     *  @return array
     */
    public function createGetProductsApiResponse($products)
    {
        return [
            'Response' => [
                'reasonCode' => '0',
                'reasonText' => $this->translator->trans('api.response.success.message'),
                'products' => $products
            ]
        ];
    }
}
