<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus;

use Interop\Queue\PsrContext;
use SimpleBus\Asynchronous\Publisher\Publisher;
use SimpleBus\Asynchronous\Routing\RoutingKeyResolver;
use SimpleBus\Serialization\Envelope\Serializer\MessageInEnvelopeSerializer;

final class SimpleBusPublisher implements Publisher
{
    private $serializer;
    private $queueResolver;
    private $context;

    public function __construct(MessageInEnvelopeSerializer $serializer, RoutingKeyResolver $queueResolver, PsrContext $context)
    {
        $this->serializer = $serializer;
        $this->queueResolver = $queueResolver;
        $this->context = $context;
    }

    public function publish($message): void
    {
        $queue = $this->context->createQueue($this->queueResolver->resolveRoutingKeyFor($message));

        $message = $this->context->createMessage($this->serializer->wrapAndSerialize($message));

        $this->context->createProducer()->send($queue, $message);
    }
}
