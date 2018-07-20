# Installation

This page explains how to install and configure _SimpleBus_ and _Enqueue_ with _Symfony_.

## Composer

Install packages with composer:

```bash
$ composer require simple-bus/asynchronous-bundle enqueue/enqueue-bundle damax/enqueue-simple-bus-queue-interop
```

Don't forget to choose appropriate [transport layer](https://github.com/php-enqueue/enqueue-dev/blob/master/docs/index.md#transports) e.g. _Redis_:

```bash
$ composer require enqueue/redis
```

If you use _Doctrine_ below bridge is recommended:

```bash
$ composer require simple-bus/doctrine-orm-bridge
```

To enable _LongRunning_ integration you'll also need:

```bash
$ composer require long-running/long-running
``` 

And finally make sure _symfony/serializer_ is present (in recent _Symfony_ versions it is not installed by default):

```bash
$ composer require symfony/serializer
```

If you prefer _jms/serializer_ over _symfony/serializer_, please do:

```
$ composer require simple-bus/jms-serializer-bundle-bridge
```
 
## Bundles

With introduction of _symfony/flex_ you don't have to worry about enabling relevant bundles, but make sure below is present in your configuration. 

```php
// Symfony v4.0 example, but v3.x is also supported.

// SimpleBus
SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle::class => ['all' => true],
SimpleBus\SymfonyBridge\SimpleBusEventBusBundle::class => ['all' => true],
SimpleBus\SymfonyBridge\DoctrineOrmBridgeBundle::class => ['all' => true],
SimpleBus\AsynchronousBundle\SimpleBusAsynchronousBundle::class => ['all' => true],

// Enqueue
Enqueue\Bundle\EnqueueBundle::class => ['all' => true],
Enqueue\SimpleBus\Bridge\Symfony\Bundle\EnqueueSimpleBusBundle::class => ['all' => true],

// Integrations
LongRunning\Bundle\LongRunningBundle\LongRunningBundle::class => ['all' => true],
```

Don't worry too much about missing out some bundle. _EnqueueSimpleBusBundle_ will warn you about it.

## Configuration

_EnqueueBundle_ suggests the following config:

```yaml
enqueue:
    transport:
        default: '%env(ENQUEUE_DSN)%'
    client: ~
```

In case of _Redis_ the value could be `ENQUEUE_DSN=redis://localhost:6379/0`.

Commands and/or events are published through _SimpleBus_ i.e. _Enqueue_ client's features are not used. 
Unless you want to explicitly publish messages through _Enqueue_ (without _SimpleBus_), the config could be trimmed down to the following:

```yaml
enqueue:
    transport:
        default: '%env(ENQUEUE_DSN)%'
``` 

Above will reduce the amount of registered services in dependency injection container.

No other configuration is required. Asynchronous messages are turned off by default.

## Next

Read next how to enable and [configure](configuration.md) asynchronous commands and events.
