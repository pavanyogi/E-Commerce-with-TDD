<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product/list", name="product_list", methods={"GET", "OPTIONS"})
     */
    public function getProductListAction(Request $request)
    {

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
