<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends Controller
{
    /**
     * @Route("/customer", name="customer", methods={"GET"})
     */
    public function customerAction(Request $request)
    {
        $customerService = $this->container->get('app.service.customer');

        // GetAll Call
        $getAll = $customerService->getAll();
        // print_r($getAll); die();

        // GetOne call
        $getOne = $customerService->getOne(1);
        // print_r($getOne); die();

        // create one call
        $createOne = $customerService->createOne(['name' => 'papu', 'dob' => '2017-12-12 00:00:00']);
        // print_r($createOne); die();

        $updateOne = $customerService->updateOne(['name' => 'papu', 'dob' => '2017-12-12 00:00:10'], $createOne);
        // print_r($updateOne); die();

        // deleteOne call
        $deleteOne = $customerService->deleteOne($createOne);
        // print_r($deleteOne); die();

        echo "success"; die();
    }
}
