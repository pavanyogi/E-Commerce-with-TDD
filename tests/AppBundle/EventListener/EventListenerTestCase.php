<?php
/**
 *  ServiceTestCase Class for providing the test case to service class.
 *
 *  @category ServiceTestCase
 *  @author Prafulla Meher
 */
namespace Tests\AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EventListenerTestCase
{
    public function requestListenerTestCase() {
        // Making first Testcase
        $testData0 = [
            'httpMethod' => Request::METHOD_OPTIONS,
            'requestType' => HttpKernelInterface::MASTER_REQUEST,
            'routeName' => 'agent_login',
            'routeParameter' => null,
            'username' => 'Prafulla Meher',
            'roles' => 'user'
        ];

        // Making second testcase
        $testData1 = [
            'httpMethod' => Request::METHOD_GET,
            'requestType' => HttpKernelInterface::SUB_REQUEST,
            'routeName' => 'agent_login',
            'routeParameter' => null,
            'username' => 'Prafulla Meher',
            'roles' => 'user'
        ];

        // Making third testcase
        $testData2 = [
            'httpMethod' => Request::METHOD_GET,
            'requestType' => HttpKernelInterface::MASTER_REQUEST,
            'routeName' => 'agent_login',
            'routeParameter' => '123',
            'username' => 'Prafulla Meher',
            'roles' => 'user'
        ];

        // Making third testcase
        $testData3 = [
            'httpMethod' => Request::METHOD_GET,
            'requestType' => HttpKernelInterface::MASTER_REQUEST,
            'routeName' => 'book_order',
            'routeParameter' => '123',
            'username' => 'Prafulla Meher',
            'roles' => 'user'
        ];

        // Making array of testcases and returning it
        return [
            $testData0,
            $testData1,
            $testData2,
            $testData3
        ];
    }

    public function responseListenerTestCase() {
        // Making first test case (Does Nothing For SubRequests)
        $testData0 = [
            $requestType = HttpKernelInterface::SUB_REQUEST
        ];

        // Making second test case
        $testData1 = [
            $requestType = HttpKernelInterface::MASTER_REQUEST
        ];

        // Making array of testcase and returning it
        return [
            $testData0,
            $testData1
        ];
    }
}