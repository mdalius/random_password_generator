# Random password generator

A small PHP application that generates passwords using `random_int()`.
Requires PHP 8.3 or later; the application needs no Composer dependencies or
additional PHP extensions. The historical PHP 5 compatibility library in `lib/`
is no longer loaded or included in the Docker image.

## Continuous integration

`.github/workflows/ci.yml` runs on pull requests and manual `workflow_dispatch`
events. All three PHP versions (8.3, 8.4, 8.5) must pass:

1. PHP syntax checks and PHPStan level 6 static analysis.
2. PHPUnit 12.5 tests with PCOV line coverage of at least 90%.
3. Docker image builds and HTTP smoke tests that check password lengths,
   character sets, cache headers, private file access, and the non-root user.

Coverage includes both application files, `random.php` and `index.php`.
Third-party compatibility code, tests, and CI utilities are excluded.
Change `CODE_COVERAGE_THRESHOLD` in the workflow to adjust the minimum.

The generator now rejects zero and negative lengths. It uses `strlen()` for
its byte-based keyspace, so the runtime does not need `mbstring`.
Tests use fixed properties rather than probabilistic uniqueness assertions.

## Docker Hub publishing

Only a manually started workflow publishes images, after **all** checks and
Docker smoke tests have passed. Pull requests never log in or push images.

In GitHub repository **Settings → Secrets and variables → Actions**, configure:

| Secret | Value |
| --- | --- |
| `DOCKER_HUB_USERNAME` | Docker Hub username |
| `DOCKER_HUB_TOKEN` | Docker Hub access token with permission to push |

Create the Docker Hub repository `<username>/random-password-generator`.
The publish jobs use the GitHub `production` environment; configure any desired
deployment restrictions under **Settings → Environments**.

Run **Actions → Continuous Integration → Run workflow**. Published tags are:

- `php8.3`, `php8.4`, `php8.5`
- `sha-<full-git-sha>-php8.3`, `sha-<full-git-sha>-php8.4`, `sha-<full-git-sha>-php8.5`
- `latest` (PHP 8.5)

Images target the runner's native Linux AMD64 architecture. Each PHP version
has a separate BuildKit cache. Publishing rebuilds using that cache from the
same checked-out commit; no test tools or coverage reports enter the image.

## Run with Docker

```sh
docker build --build-arg PHP_VERSION=8.5 -t random-password-generator .
docker run --rm -p 8080:8080 random-password-generator
```

Open <http://localhost:8080>. To use a published image:

```sh
docker run --rm -p 8080:8080 <username>/random-password-generator:latest
```

The image uses official `php:<version>-fpm-alpine` plus Alpine Nginx. It runs as
`www-data`, exposes HTTP on port 8080, keeps FPM on container-local port 9000,
and serves only `/` and `/index.php`. It uses PHP's production configuration
and includes an HTTP health check. The build context allowlist contains only
the application and Docker configuration. No Ubuntu base, Composer, PHPUnit,
development extensions, or legacy library is added. CI prints the actual image
size, which varies with the upstream PHP and Alpine releases.

For HTTPS, place the container behind your existing TLS reverse proxy.

## Run checks locally

Install PHP 8.3+ and PHPUnit 12.5 / PHPStan 2 (for example, their PHAR releases).
PHPUnit additionally requires DOM, mbstring, XML and XMLWriter; coverage needs
PCOV or Xdebug. The coverage threshold script needs SimpleXML.

```sh
find . -name '*.php' -not -path './.git/*' -not -path './vendor/*' -print0 | xargs -0 -n 1 php -l
phpstan analyse --no-progress
phpunit --stderr
php tools/check-coverage.php build/coverage.xml
```

`--stderr` keeps PHPUnit's progress output separate from the page rendering test
so HTTP headers can be set. If using downloaded PHARs, replace `phpunit` and
`phpstan` with `php phpunit.phar` and `php phpstan.phar`. For Xdebug, set
`XDEBUG_MODE=coverage` when running PHPUnit.
