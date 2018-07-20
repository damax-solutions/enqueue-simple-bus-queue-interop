# Configuration

## Commands

The minimal config to enable asynchronous commands is the following:

```yaml
enqueue_simple_bus:
    commands: ~
```

By default asynchronous commands published through _SimpleBus_ will be routed to __asynchronous_commands__ queue.

For consumption `enqueue.simple_bus.commands_processor` service is registered. Console example:

```bash
./bin/console enqueue:transport:consume enqueue.simple_bus.commands_processor --queue=asynchronous_commands
``` 

You can customize queue name with:

```yaml
enqueue_simple_bus:
    commands: async_jobs
```

The processor service id could also be customized:

```yaml
enqueue_simple_bus:
    commands:
        processor_service_id: commands_processor
        default_queue: async_jobs
```

The console command for above config:

```bash
./bin/console enqueue:transport:consume commands_processor --queue=async_jobs
```

It is possible to route messages to different queues. Could be useful for heavy consumption processes. 

```yaml
enqueue_simple_bus:
    commands:
        default_queue: async_commands
        queue_map:
            App\Command\ResizeImage: resize_images
            App\Command\SendConfirmationEmail: transactional_emails
            App\Command\SendNotificationEmail: transactional_emails
```

`App\Command\ResizeImage` command will be routed to `resize_images` queue. Email commands will be routed to `transactional_emails` queue.
Everything else goes to `async_commands`.

Relevant console commands:

```bash
./bin/console enqueue:transport:consume enqueue.simple_bus.commands_processor --queue=resize_images
./bin/console enqueue:transport:consume enqueue.simple_bus.commands_processor --queue=transactional_emails
./bin/console enqueue:transport:consume enqueue.simple_bus.commands_processor --queue=async_commands

# You can combine queues consumption in one process.
./bin/console enqueue:transport:consume enqueue.simple_bus.commands_processor --queue=resize_images --queue=async_commands
```

For advanced setup it is possible to customize _Enqueue_ context (transport) name:

```yaml
enqueue_simple_bus:
    commands:
        transport_name: rabbit # Uses 'enqueue.transport.rabbit.context' service.
```

## Events

Asynchronous events are configured in the same way:

```yaml
enqueue_simple_bus:
    events: ~
```

By default asynchronous events published through _SimpleBus_ will be routed to __asynchronous_events__ queue.

For consumption `enqueue.simple_bus.events_processor` service is registered. Console example:

```bash
./bin/console enqueue:transport:consume enqueue.simple_bus.events_processor --queue=asynchronous_events
``` 

You can customize default queue name, processor service id and configure events mapping to specific queues in the same way as for commands. 

## Next

Read next on [usage examples](usage.md).
