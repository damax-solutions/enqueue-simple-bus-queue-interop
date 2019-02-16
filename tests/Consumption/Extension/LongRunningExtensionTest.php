<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Consumption\Extension;

use Enqueue\Consumption\Context\PostMessageReceived;
use Enqueue\Null\NullContext;
use Enqueue\SimpleBus\Consumption\Extension\LongRunningExtension;
use LongRunning\Core\Cleaner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

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
    public function it_runs_cleanup_on_post_message_received()
    {
        $this->cleaner
            ->expects($this->once())
            ->method('cleanUp')
        ;

        $this->extension->onPostMessageReceived($this->createEvent());
    }

    private function createEvent(): PostMessageReceived
    {
        $context = new NullContext();
        $destination = $context->createTopic('test');
        $consumer = $context->createConsumer($destination);
        $logger = $this->createMock(LoggerInterface::class);

        return new PostMessageReceived($context, $consumer, $context->createMessage(), 'OK', time(), $logger);
    }
}
