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
}
