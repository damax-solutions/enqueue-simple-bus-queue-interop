# SimpleBus + Queue Interop + Enqueue 

[![Build Status](https://travis-ci.org/damax-solutions/enqueue-simple-bus-queue-interop.svg?branch=master)](https://travis-ci.org/damax-solutions/enqueue-simple-bus-queue-interop) [![Coverage Status](https://coveralls.io/repos/damax-solutions/enqueue-simple-bus-queue-interop/badge.svg?branch=master&service=github)](https://coveralls.io/github/damax-solutions/enqueue-simple-bus-queue-interop?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/damax-solutions/enqueue-simple-bus-queue-interop/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/damax-solutions/enqueue-simple-bus-queue-interop/?branch=master)

[SimpleBus](https://simplebus.io) integration with [Queue Interop](https://github.com/queue-interop/queue-interop) via [Enqueue](https://enqueue.forma-pro.com) library.

Check out this [sample application](https://github.com/lakiboy/damax-common/tree/master/sample) how to implement async domain events.

## Features

- Send asynchronous _SimpleBus_ commands or events with _queue-interop_.
- Consume _SimpleBus_ messages with _Enqueue_ consumption layer.
- [LongRunning](https://github.com/LongRunning/LongRunning) library integration to avoid memory leaks.
- Integration with [Symfony Framework](https://github.com/symfony/symfony) with almost zero configuration.
- Map _SimpleBus_ messages to specific queues or send everything into single location.
- Serialize with standard _Symfony_ tools i.e. [jms/serializer](https://github.com/schmittjoh/serializer) is not required.

## Documentation

Topics:

- [Installation](doc/installation.md)
- [Configuration](doc/configuration.md)
- [Usage](doc/usage.md)
- [Development](doc/development.md)
