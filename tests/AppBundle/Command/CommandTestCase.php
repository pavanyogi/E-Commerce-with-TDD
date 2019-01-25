<?php
/**
 *  CommandTestCase Class for providing the test case to command class.
 *
 *  @category CommandTestCase
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
