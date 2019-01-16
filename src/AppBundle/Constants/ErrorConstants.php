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

    public static $errorCodeMap = [
        self::INTERNAL_ERR => ['code' => '500', 'message' => 'api.response.error.internal_error'],
    ];
}