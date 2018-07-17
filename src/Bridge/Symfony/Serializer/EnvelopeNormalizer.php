<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Bridge\Symfony\Serializer;

use SimpleBus\Serialization\Envelope\DefaultEnvelope;
use SimpleBus\Serialization\Envelope\Envelope;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EnvelopeNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        /* @var Envelope $object */

        return [
            'type' => $object->messageType(),
            'message' => $object->serializedMessage(),
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return DefaultEnvelope::forSerializedMessage($data['type'], $data['message']);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Envelope;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_a($type, Envelope::class, true);
    }
}
