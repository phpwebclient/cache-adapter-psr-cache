<?php

declare(strict_types=1);

namespace Webclient\Cache\Adapter\PsrCache;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Webclient\Cache\Contract\CacheInterface;
use Webclient\Cache\Contract\Exception\CacheError;

final class PsrCacheAdapter implements CacheInterface
{
    private CacheItemPoolInterface $cache;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): ?string
    {
        try {
            $item = $this->cache->getItem($key);
        } catch (InvalidArgumentException $exception) {
            throw new CacheError($exception->getMessage(), $exception->getCode(), $exception);
        }
        if (!$item->isHit()) {
            return null;
        }
        $value = $item->get();
        if (!is_string($value)) {
            return null;
        }
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, string $data, ?int $ttl = null): void
    {
        try {
            $item = $this->cache->getItem($key);
        } catch (InvalidArgumentException $exception) {
            throw new CacheError($exception->getMessage(), $exception->getCode(), $exception);
        }
        $item->set($data);
        $item->expiresAfter($ttl);
        $this->cache->save($item);
    }
}
