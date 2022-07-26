[![Latest Stable Version](https://img.shields.io/packagist/v/webclient/cache-adapter-psr-cache.svg?style=flat-square)](https://packagist.org/packages/webclient/cache-adapter-psr-cache)
[![Total Downloads](https://img.shields.io/packagist/dt/webclient/cache-adapter-psr-cache.svg?style=flat-square)](https://packagist.org/packages/webclient/cache-adapter-psr-cache/stats)
[![License](https://img.shields.io/packagist/l/webclient/cache-adapter-psr-cache.svg?style=flat-square)](https://github.com/phpwebclient/cache-adapter-psr-cache/blob/master/LICENSE)
[![PHP](https://img.shields.io/packagist/php-v/webclient/cache-adapter-psr-cache.svg?style=flat-square)](https://php.net)

# webclient/cache-adapter-psr-cache

[psr/cache](https://packagist.org/packages/psr/cache) adapter for [webclient/cache-contract](https://packagist.org/packages/webclient/cache-contract)

# Install

Install this package and your favorite [psr-6 implementation](https://packagist.org/providers/psr/cache-implementation).

Install this package
```bash
composer require webclient/cache-adapter-psr-cache:^3.0
```

# Usage
```php
<?php

/** @var \Psr\Cache\CacheItemPoolInterface $psrCache */
$psrCacheAdapter = new \Webclient\Cache\Adapter\PsrCache\PsrCacheAdapter($psrCache);
```
