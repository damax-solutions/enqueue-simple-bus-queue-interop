<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Bridge\Symfony\Serializer;

use Enqueue\SimpleBus\Bridge\Symfony\Serializer\EnvelopeNormalizer;
use PHPUnit\Framework\TestCase;
use SimpleBus\Serialization\Envelope\DefaultEnvelope;
use SimpleBus\Serialization\Envelope\Envelope;
use stdClass;

class EnvelopeNormalizerTest extends TestCase
{
    /**
     * @var EnvelopeNormalizer
     */
    private $normalizer;

    protected function setUp()
    {
        $this->normalizer = new EnvelopeNormalizer();
    }

    /**
     * @test
     */
    public function it_checks_normalization_is_supported()
    {
        $msg = new stdClass();

        $this->assertTrue($this->normalizer->supportsNormalization(DefaultEnvelope::forMessage($msg)));
        $this->assertFalse($this->normalizer->supportsNormalization($msg));
    }

    /**
     * @test
     */
    public function it_checks_denormalization_is_supported()
    {
        $this->assertTrue($this->normalizer->supportsDenormalization('__data__', DefaultEnvelope::class));
        $this->assertFalse($this->normalizer->supportsDenormalization('__data__', stdClass::class));
    }

    /**
     * @test
     */
    public function it_normalizes_envelope()
    {
        $msg = new stdClass();

        $envelope = DefaultEnvelope::forMessage($msg)->withSerializedMessage('__serialized__');

        $this->assertEquals(['type' => stdClass::class, 'message' => '__serialized__'], $this->normalizer->normalize($envelope));
    }

    /**
     * @test
     */
    public function it_denormalizes_envelope()
    {
        $envelope = $this->normalizer->denormalize(['type' => stdClass::class, 'message' => '__serialized__'], Envelope::class);

        $this->assertInstanceOf(Envelope::class, $envelope);
        $this->assertEquals(stdClass::class, $envelope->messageType());
        $this->assertEquals('__serialized__', $envelope->serializedMessage());
    }
}
