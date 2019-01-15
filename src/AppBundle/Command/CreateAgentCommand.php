<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateAgentCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-agent';

    protected function configure()
    {
        $this->setDescription('Creates a new Agent.')
            ->setHelp('This command allows you to create an Agent...');

        $this->addArgument('Agentname', InputArgument::REQUIRED, 'The agentname of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Agent Created',
            '============',
            '',
        ]);

        // retrieve the argument value using getArgument()
        $output->writeln('Agentname: '.$input->getArgument('Agentname'));
    }
}