<?php
/**
 *  Service Class for Creating Sample Test Case.
 *
 *  @category SampleTestCase
 *  @author Prafulla Meher
 */
namespace Tests\AppBundle\Command;

use AppBundle\Constants\ErrorConstants;

class CommandTestCase
{
    public function getCreateAgentCommandTestCase() {
        $testCase0 = [
            'agentName' => 'TestAgent',
            'emailID' => 'test@gmail.com',
            'password' => '123'
        ];
        $testCase = [
            $testCase0
        ];

        return $testCase;
    }
}
