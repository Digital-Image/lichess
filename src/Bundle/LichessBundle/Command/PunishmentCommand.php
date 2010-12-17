<?php

namespace Bundle\LichessBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

/**
 * Redistributes a cheater ELO to his victims
 */
class PunishmentCommand extends BaseCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'Username of the cheater'),
            ))
            ->setName('lichess:cheat:punish')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $user = $this->container->get('doctrine_user.repository.user')->findOneByUsername($username);
        if(!$user) {
            throw new \InvalidArgumentException(sprintf('The user "%s" does not exist', $username));
        }
        $this->container->get('lichess.cheat.punisher')->punish($user);
    }
}
