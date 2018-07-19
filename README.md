# SimpleBus + Queue Interop + Enqueue 

[![Build Status](https://travis-ci.org/lakiboy/enqueue-simple-bus-queue-interop.svg?branch=master)](https://travis-ci.org/lakiboy/enqueue-simple-bus-queue-interop) [![Coverage Status](https://coveralls.io/repos/lakiboy/enqueue-simple-bus-queue-interop/badge.svg?branch=master&service=github)](https://coveralls.io/github/lakiboy/enqueue-simple-bus-queue-interop?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lakiboy/enqueue-simple-bus-queue-interop/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lakiboy/enqueue-simple-bus-queue-interop/?branch=master)

[SimpleBus](https://simplebus.io) integration with [Enqueue](https://enqueue.forma-pro.com) library via [Queue Interop](https://github.com/queue-interop/queue-interop).

## Features

- Send asynchronous _SimpleBus_ commands or events with _queue-interop_.
- Consume _SimpleBus_ messages with _Enqueue_ consumption layer.
- [LongRunning](https://github.com/LongRunning/LongRunning) library integration to avoid memory leaks.
- Integration with [Symfony Framework](https://github.com/symfony/symfony) with almost zero configuration.
- Map _SimpleBus_ messages to specific queues or send everything into single location.
- Serialize with standard _Symfony_ tools i.e. _JMSSerializer_ is not required.

## Documentation

Topics:

- [Development](doc/development.md)
