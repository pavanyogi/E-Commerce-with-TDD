<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{
    /**
     * @Route("/order/list", name="order_list", methods={"GET", "OPTIONS"})
     */
    public function getOrderListAction(Request $request)
    {

    }

    /**
     * @Route("/order/detail", name="order_detail", methods={"GET", "OPTIONS"})
     */
    public function getOrderDetailAction(Request $request)
    {

    }

    /**
     * @Route("/book_order", name="book_order", methods={"POST", "OPTIONS"})
     */
    public function placeOrderAction(Request $request)
    {

    }
}
