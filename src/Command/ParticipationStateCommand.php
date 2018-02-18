<?php

namespace App\Command;

use App\Service\ParticipationManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParticipationStateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('odr:participation:synchronise-state')
            ->setDescription('Synchronise participation state')
            ->setHelp('This command allows you to synchronise participation state...');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start synchronisation.',
            '============',
            '',
        ]);
        $this->getContainer()->get(ParticipationManager::class)->bulkUpdateState();

        $output->writeln('Synchronisation done.');
    }
}
