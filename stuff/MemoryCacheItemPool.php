<?php

declare(strict_types=1);

namespace Stuff\Webclient\Cache\Adapter\PsrCache;

use DateTimeImmutable;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class MemoryCacheItemPool implements CacheItemPoolInterface
{
    /**
     * @var CacheItem[]
     */
    private array $storage = [];

    /**
     * @var CacheItem[]
     */
    private array $deferred = [];

    public function getItem(string $key): CacheItemInterface
    {
        if (!$this->hasItem($key)) {
            return new CacheItem($key);
        }
        return $this->storage[$key];
    }

    public function getItems(array $keys = []): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->getItem($key);
        }
        return $result;
    }

    public function hasItem(string $key): bool
    {
        if (!array_key_exists($key, $this->storage)) {
            return false;
        }
        $item = $this->storage[$key];
        $expires = $item->getExpires();
        $now = new DateTimeImmutable();
        if (is_null($expires) || $now < $expires) {
            return true;
        }
        unset($this->storage[$key]);
        return false;
    }

    public function clear(): bool
    {
        $this->storage = [];
        $this->deferred = [];
        return true;
    }

    public function deleteItem(string $key): bool
    {
        if ($this->hasItem($key)) {
            unset($this->storage[$key]);
        }
        return true;
    }

    public function deleteItems(array $keys): bool
    {
        $result = true;
        foreach ($keys as $key) {
            if (!$this->deleteItem($key)) {
                $result = false;
            }
        }
        return $result;
    }

    public function save(CacheItemInterface $item): bool
    {
        $this->storage[$item->getKey()] = $item;
        return true;
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

    public function commit(): bool
    {
        foreach ($this->deferred as $item) {
            $this->storage[$item->getKey()] = $item;
        }
        $this->deferred = [];
        return true;
    }
}
