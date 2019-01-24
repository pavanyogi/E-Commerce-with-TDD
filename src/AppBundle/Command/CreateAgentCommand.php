<?php
/**
 *  Command Class for creating the Agent
 *
 *  @category Service
 *  @author Prafulla Meher
 */
namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CreateAgentCommand extends ContainerAwareCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-agent';

    /**
     *  Function to add configuration for Commnand.
     *
     *  @return void
     */
    protected function configure()
    {
        $this->setDescription('Creates a new Agent.')
            ->setHelp('This command allows you to create an Agent...');

        $this->addArgument('agentName', InputArgument::REQUIRED, 'The Agent Name')
            ->addArgument('emailID', InputArgument::REQUIRED, 'Agent Email ID')
            ->addArgument('password', InputArgument::REQUIRED, 'Agent Default Password');
    }

    /**
     * Function to process the command inputs, process the data and write output of processing.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        $user = $userManipulator
            ->create(
                $input->getArgument('agentName'), $input->getArgument('password'),
                $input->getArgument('password'), true, false
            );

        $output->writeln([
            'Agent Created',
            '============',
            '',
        ]);

        // retrieve the argument value using getArgument()
        $output->writeln('Agentname: '.$user->getUsername());
    }
}