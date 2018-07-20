# Usage

## Production

Read _Enqueue_ [documentation](https://github.com/php-enqueue/enqueue-dev/blob/master/docs/bundle/production_settings.md) regarding running consumption in production.

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

If you want to contribute read on how to [run the code locally](development.md) in Docker.  
