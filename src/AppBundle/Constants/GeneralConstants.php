<?php
/**
 *  ResourceRolePermissionMap Class for defining Permissions Map for the resource based on there roles.
 *
 *  @author Prafulla Meher
 */
namespace AppBundle\Constants;
use Symfony\Component\HttpFoundation\Request;

final class GeneralConstants
{
    const GET_PRODUCT_URL = '/api/product/list';
    const UPDATE_PRODUCT_URL = '/api/product/update';
    const DETAIL_PRODUCT_URL = '/api/product/detail';
    const CREATE_PRODUCT_URL = '/api/product/create';

    public static $urlMethodMap = [
        self::GET_PRODUCT_URL => [
            'method' => Request::METHOD_GET
        ],
        self::UPDATE_PRODUCT_URL => [
            'method' => Request::METHOD_PUT
        ],
        self::DETAIL_PRODUCT_URL => [
            'method' => Request::METHOD_GET
        ],
        self::CREATE_PRODUCT_URL => [
            'method' => Request::METHOD_POST
        ]
    ];
}