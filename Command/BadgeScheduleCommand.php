<?php

namespace Summa\Bundle\BadgeBundle\Command;

use Doctrine\Common\Persistence\ManagerRegistry;
use Oro\Component\MessageQueue\Client\MessageProducerInterface;
use Summa\Bundle\BadgeBundle\Async\Topics;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Oro\Bundle\CronBundle\Command\CronCommandInterface;
use Summa\Bundle\BadgeBundle\Entity\Badge;

/**
 * Execute update or remove Product-Badge for Badges with conditions
 */
#[\Symfony\Component\Console\Attribute\AsCommand('oro:cron:badge:schedule', 'Execute update or remove Product-Badge for Badges with conditions')]
class BadgeScheduleCommand extends Command implements \Oro\Bundle\CronBundle\Command\CronCommandActivationInterface, \Oro\Bundle\CronBundle\Command\CronCommandScheduleDefinitionInterface
{
    /** @var ManagerRegistry */
    private $registry;
    /** @var MessageProducerInterface */
    protected $messageProducer;
    /**
     * @param ManagerRegistry $registry
     * @param MessageProducerInterface $messageProducer
     */
    public function __construct(
        ManagerRegistry $registry,
        MessageProducerInterface $messageProducer
    ) {
        $this->registry = $registry;
        $this->messageProducer = $messageProducer;
        parent::__construct();
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
    }
    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        //Todo: implement flag enable or disable.
        //$offsetHours = $this->configManager->get('oro_pricing.offset_of_processing_cpl_prices');

        return true;
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $badgesToProcess = $this->registry
            ->getManagerForClass(Badge::class)
            ->getRepository(Badge::class)
            ->getActiveBadgesCroneable();

        foreach ($badgesToProcess as $badge){
            $this->messageProducer->send(
                \Summa\Bundle\BadgeBundle\Async\Topic\ResolveBadgeAssignedProductsTopic::getName(),
                [
                    'badge_id' => $badge->getId()
                ]
            );
        }
        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
    /**
     * {@inheritDoc}
     */
    public function getDefaultDefinition(): string
    {
        return '0 * * * *';
    }
}
