<?php

namespace App\Command;

use App\Service\ParticipationManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParticipationCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('odr:participation:resend')
            ->setDescription('Resend participation')
            ->setHelp('This command allows you to resend participation not sent in API Sogec...');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start resend participation.',
            '============',
            '',
        ]);
        $this->getContainer()->get(ParticipationManager::class)->bulkResendParticipation();

        $output->writeln('Resend participation done.');
    }
}
