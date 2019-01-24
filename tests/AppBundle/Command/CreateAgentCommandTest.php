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
use Doctrine\Common\Persistence\ObjectManager;

class CreateAgentCommandTest extends KernelTestCase
{
    /** @var Application */
    private $application;
    /** @var CommandTestCase */
    private $commandTestCase;
    /** @var ObjectManager */
    private $entityManager;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
    }

    public function setUp()
    {
        parent::setUp();
        $this->application = new Application(self::$kernel);
        $this->commandTestCase = new CommandTestCase();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->application = null;

        // Removing the User from the Database after Successful Creation
        $userRepo = $this->entityManager->getRepository('AppBundle:user');
        $createAgentCommandTestCases = $this->commandTestCase->getCreateAgentCommandTestCase();
        foreach ($createAgentCommandTestCases as $testCase) {
            $user = $userRepo->findOneBy(['username' => $testCase['agentName']]);
            if($user) {
                $this->entityManager->remove($user);
            }
        }
        $this->entityManager->flush();
    }

    public function testExecuteCreateAgentCommand()
    {
        // Testing the command by calling the command.
        $command = $this->application->find('app:create-agent');
        $commandTester = new CommandTester($command);

        // Fetching the TestCases from SampleTestCase Class
        $createAgentCommandTestCases = $this->commandTestCase->getCreateAgentCommandTestCase();
        foreach ($createAgentCommandTestCases as $testCase) {
            $testCase['command'] = $command->getName();
            $commandTester->execute($testCase);
        }

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        // asserting the output content
        $this->assertContains('Agentname: TestAgent', $output);
    }
}