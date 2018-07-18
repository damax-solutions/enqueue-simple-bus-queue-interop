<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Consumption\Extension;

use Enqueue\Consumption\Context;
use Enqueue\SimpleBus\Consumption\Extension\LongRunningExtension;
use Interop\Queue\PsrContext;
use LongRunning\Core\Cleaner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LongRunningExtensionTest extends TestCase
{
    /**
     * @var Cleaner|MockObject
     */
    private $cleaner;

    /**
     * @var LongRunningExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->cleaner = $this->createMock(Cleaner::class);
        $this->extension = new LongRunningExtension($this->cleaner);
    }

    /**
     * @test
     */
    public function it_runs_cleanup_on_post_received()
    {
        $this->cleaner
            ->expects($this->once())
            ->method('cleanUp')
        ;

        $context = $this->createMock(PsrContext::class);

        $this->extension->onPostReceived(new Context($context));
    }
}
