<?php

namespace Summa\Bundle\BadgeBundle\Async\Topic;

class ResolveBadgeAssignedProductsTopic extends \Oro\Component\MessageQueue\Topic\AbstractTopic
{
    public static function getName(): string
    {
        return 'summa_product_badge.badge.resolve_assigned_products';
    }
    public static function getDescription(): string
    {
        // TODO: Implement getDescription() method.
        return '';
    }
    public function configureMessageBody(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        // TODO: Implement configureMessageBody() method.
    }
}
