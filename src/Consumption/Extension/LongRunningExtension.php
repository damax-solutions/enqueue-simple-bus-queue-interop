<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Consumption\Extension;

use Enqueue\Consumption\Context\PostMessageReceived;
use Enqueue\Consumption\PostMessageReceivedExtensionInterface;
use LongRunning\Core\Cleaner;

final class LongRunningExtension implements PostMessageReceivedExtensionInterface
{
    private $cleaner;

    public function __construct(Cleaner $cleaner)
    {
        $this->cleaner = $cleaner;
    }

    public function onPostMessageReceived(PostMessageReceived $context): void
    {
        $this->cleaner->cleanUp();
    }
}
