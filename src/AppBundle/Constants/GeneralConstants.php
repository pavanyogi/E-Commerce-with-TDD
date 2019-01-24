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
    public const PRODUCT_STATUS_ACTIVE = 'active';
    public const PRODUCT_STATUS_LOCKED = 'locked';

    const GET_PRODUCT_URL = '/api/product/list';
    const UPDATE_PRODUCT_URL = '/api/product/update';
    const DETAIL_PRODUCT_URL = '/api/product/detail';
    const CREATE_PRODUCT_URL = '/api/product/create';
    Const LOGIN_URL = '/api/agent/login';
    const PLACE_ORDER_URL = '/api/book_order';
    const GET_CUSTOMER_LIST_URL = '/api/customer/list';
    const GET_CUSTOMER_DETAIL_URL = '/api/customer/detail';
    const CREATE_CUSTOMER_URL = '/api/customer/create';

    const ROLE_AGENT = 'ROLE_AGENT';

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
        ],
        self::LOGIN_URL => [
            'method' => Request::METHOD_POST
        ],
        self::PLACE_ORDER_URL => [
            'method' => Request::METHOD_POST
        ],
        self::GET_CUSTOMER_LIST_URL => [
            'method' => Request::METHOD_GET
        ],
        self::GET_CUSTOMER_DETAIL_URL => [
            'method' => Request::METHOD_GET
        ],
        self::CREATE_CUSTOMER_URL => [
            'method' => Request::METHOD_POST
        ]
    ];

    public static $productStatusMap = [
        self::PRODUCT_STATUS_ACTIVE => 1,
        self::PRODUCT_STATUS_LOCKED => 2
    ];
}