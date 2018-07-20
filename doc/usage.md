# Usage

This page refers to relevant documentation sections of [SimpleBus](http://docs.simplebus.io) and [Enqueue](https://github.com/php-enqueue/enqueue-dev/blob/master/docs/index.md) projects.  

## SimpleBus

To route messages via _queue-interop_ commands and events must be registered in container as __asynchronous__.

#### Commands

XML definition example:

```xml
<service id="App\Handler\RegisterTransactionHandler" public="true">
    <tag name="asynchronous_command_handler" handles="App\Handler\RegisterTransaction" method="registerTransaction" />
</service>

<!-- Attribute 'method' can be omitted if handler implements __invoke() -->

<service id="App\Handler\RegisterTransactionHandler" public="true">
    <tag name="asynchronous_command_handler" handles="App\Handler\RegisterTransaction" />
</service>
```

#### Events

XML definition example:

```xml
<service id="App\Listener\MailerListener" public="true">
    <tag name="asynchronous_event_subscriber" subscribes_to="App\Event\UserCreated" method="onUserRegistered" />
</service>
```

## Production

Read _Enqueue_ [documentation](https://github.com/php-enqueue/enqueue-dev/blob/master/docs/bundle/production_settings.md) regarding running queue consumer in production.

Example _supervisord_ config:

```
[program:domain_events]
command=./bin/console enqueue:transport:consume enqueue.simple_bus.events_processor --queue=domain_events --time-limit=15minutes --idle-timeout=1000
directory = /var/www/project/prod/current
user = symfony
autostart = true
autorestart = true
stderr_logfile = /var/log/supervisor/domain_events-stderr.log
stdout_logfile = /var/log/supervisor/domain_events-stdout.log
```

## Next

If you wish to contribute take a look how to [run the code locally](development.md) in Docker.
