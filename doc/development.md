# Development

Build image:

```bash
$ docker build -t enqueue-simple-bus-queue-interop .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w /app enqueue-simple-bus-queue-interop composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app enqueue-simple-bus-queue-interop composer cs
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w /app enqueue-simple-bus-queue-interop composer test
$ docker run --rm -v $(pwd):/app -w /app enqueue-simple-bus-queue-interop composer test-cc
```
