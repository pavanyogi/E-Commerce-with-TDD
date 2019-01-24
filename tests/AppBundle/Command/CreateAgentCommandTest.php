<?php
/**
 *  Class for testing the createAgent command
 *
 *  @category CommandTester
 *  @author Prafulla Meher
 */
namespace Tests\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateAgentCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        // Testing the command by calling the command.
        $command = $application->find('app:create-agent');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            'agentName' => 'TestAgent',
            'emailID' => 'test@gmail.com',
            'password' => '123'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Agentname: TestAgent', $output);
    }
}