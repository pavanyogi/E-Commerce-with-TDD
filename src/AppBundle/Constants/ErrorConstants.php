<?php
/**
 *  Error Constants file for Storing Error Message codes and Message Text for Application.
 *
 *  @category Constants
 *  @author Prafulla Meher<prafulla.m@mindfiresolutions.com>
 */

namespace AppBundle\Constants;

final class ErrorConstants
{
    const INTERNAL_ERR = 'INTERNALERR';
    const INVALID_PROUCT_CODE = 'INVALIDPROUCTCODE';
    const INVALID_CRED = 'INVALIDCRED';
    const DISABLEDUSER = 'DISABLEDUSER';
    const INVALID_ROLE = 'INVALIDROLE';
    const INVALID_CONTENT_TYPE = 'INVALIDCONTENTTYPE';
    const INVALID_USER_NAME = 'INVALIDUSER';
    const INVALID_AUTHORIZATION = 'INVALID_AUTHORIZATION';
    const INVALID_AUTHORIZATION_OR_USER_NAME = 'INVALIDAUTHORIZATIONORUSER_NAME';
    const INVALID_PRODUCT_QUANTITY = 'INVALIDPRODUCTQUANTITY';
    const INVALID_CUSTOMER_ID = 'INVALIDCUSTOMERID';

    public static $errorCodeMap = [
        self::INTERNAL_ERR => ['code' => '500', 'message' => 'api.response.error.internal_error'],
        self::INVALID_PROUCT_CODE => ['code' => '422', 'message' => 'api.response.error.invalid_product_code'],
        self::INVALID_CRED => ['code' => '422', 'message' => 'api.response.error.invalid_credential'],
        self::DISABLEDUSER => ['code' => '422', 'message' => 'api.response.error.disabled_uesr'],
        self::INVALID_ROLE => ['code' => '422', 'message' => 'api.response.error.invalid_role'],
        self::INVALID_CONTENT_TYPE => ['code' => '1008', 'message' => 'api.response.error.invalid_content_type'],
        self::INVALID_USER_NAME => ['code' => '1008', 'message' => 'api.response.error.invalid_user_name'],
        self::INVALID_AUTHORIZATION => ['code' => '1008', 'message' => 'api.response.error.invalid_authorization_token'],
        self::INVALID_AUTHORIZATION_OR_USER_NAME => ['code' => '1008',
            'message' => 'api.response.error.invalid_authorization_or_username'],
        self::INVALID_PRODUCT_QUANTITY => ['code' => '1008',
            'message' => 'api.response.error.invalid_product_quantity'],
        self::INVALID_CUSTOMER_ID => ['code' => '1008', 'message' => 'api.response.error.invalid_customer_id']
    ];
}
