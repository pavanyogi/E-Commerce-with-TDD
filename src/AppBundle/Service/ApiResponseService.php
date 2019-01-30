<?php
/**
 *  Service Class for Creating API Request Response.
 *
 *  @category Service
 *  @author Ashish Kumar
 */
namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;

class ApiResponseService extends BaseService
{
    /**
     *  Function to create response of GET products API.
     *
     *  @param array $requestContent
     *  @param array $products
     *
     *  @return array
     */
    public function createProductsApiResponse($responseKey, $data)
    {
        return [
            'reasonCode' => '0',
            'reasonText' => $this->translator->trans('api.response.success.message'),
            $responseKey => $data,
        ];
    }

    public function createAgentApiSuccessResponse($responseKey, $data)
    {
        return [
            'Response' => [
                'reasonCode' => '0',
                'reasonText' => $this->translator->trans('api.response.success.message'),
                $responseKey => $data,
            ]
        ];
    }

    public function createOrderApiSuccessResponse($responseKey, $data)
    {
        return [
            'Response' => [
                'reasonCode' => '0',
                'reasonText' => $this->translator->trans('api.response.success.message'),
                $responseKey => $data,
            ]
        ];
    }

    public function createCustomerApiSuccessResponse($responseKey, $data)
    {
        return [
            'Response' => [
                'reasonCode' => '0',
                'reasonText' => $this->translator->trans('api.response.success.message'),
                $responseKey => $data,
            ]
        ];
    }

    /**
     *  Function to create API Error Response.
     *
     * @param string $errorCode
     * @param string $transactionId (default = null)
     *
     * @return array
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
}
