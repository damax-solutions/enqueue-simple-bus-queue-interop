# Development

Build image:

```bash
$ docker build -t damax-simple-bus-enqueue-bridge-bundle .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-simple-bus-enqueue-bridge-bundle composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-simple-bus-enqueue-bridge-bundle composer cs
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-simple-bus-enqueue-bridge-bundle composer test
$ docker run --rm -v $(pwd):/app -w /app damax-simple-bus-enqueue-bridge-bundle composer test-cc
```
