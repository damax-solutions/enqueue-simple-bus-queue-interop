<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Consumption\Extension;

use Enqueue\Consumption\Context;
use Enqueue\Consumption\EmptyExtensionTrait;
use Enqueue\Consumption\ExtensionInterface;
use LongRunning\Core\Cleaner;

final class LongRunningExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    private $cleaner;

    public function __construct(Cleaner $cleaner)
    {
        $this->cleaner = $cleaner;
    }

    public function onPostReceived(Context $context): void
    {
        $this->cleaner->cleanUp();
    }
}
