<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Bridge\Symfony\Serializer;

use SimpleBus\Serialization\ObjectSerializer as ObjectSerializerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ObjectSerializer implements ObjectSerializerInterface
{
    private const FORMAT = 'json';

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($object): string
    {
        return $this->serializer->serialize($object, self::FORMAT);
    }

    public function deserialize($serializedObject, $type)
    {
        return $this->serializer->deserialize($serializedObject, $type, self::FORMAT);
    }
}
