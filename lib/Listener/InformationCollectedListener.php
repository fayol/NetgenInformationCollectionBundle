<?php

declare(strict_types=1);

namespace Netgen\InformationCollection\Listener;

use Netgen\InformationCollection\Core\Action\ActionRegistry;
use Netgen\InformationCollection\API\Value\Event\InformationCollected;
use Netgen\InformationCollection\API\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InformationCollectedListener implements EventSubscriberInterface
{
    /**
     * @var ActionRegistry
     */
    protected $actionAggregate;

    /**
     * InformationCollectedListener constructor.
     *
     * @param ActionRegistry $actionAggregate
     */
    public function __construct(ActionRegistry $actionAggregate)
    {
        $this->actionAggregate = $actionAggregate;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::INFORMATION_COLLECTED => 'onInformationCollected',
        ];
    }

    /**
     * Run all actions.
     *
     * @param InformationCollected $event
     */
    public function onInformationCollected(InformationCollected $event)
    {
        $this->actionAggregate->act($event);
    }
}
