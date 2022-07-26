<?php

declare(strict_types=1);

namespace Stuff\Webclient\Cache\Adapter\PsrCache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Psr\Cache\CacheItemInterface;

final class CacheItem implements CacheItemInterface
{
    private string $key;
    private $value;
    private ?DateTimeInterface $expires;

    public function __construct(string $key, $value = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->expires = null;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function get()
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return $this->value !== null;
    }

    public function set($value)
    {
        $this->value = $value;
    }

    public function expiresAt($expiration)
    {
        if (is_null($expiration)) {
            $this->expires = null;
            return $this;
        }
        if ($expiration instanceof DateTimeInterface) {
            $this->expires = $expiration;
            return $this;
        }
        throw new InvalidArgumentException('expiration must be null or DateTimeInterface');
    }

    public function expiresAfter($time)
    {
        if (is_null($time)) {
            $this->expires = null;
            return $this;
        }
        if (is_int($time)) {
            $this->expires = (new DateTimeImmutable())->modify('+' . $time . ' seconds');
            return $this;
        }
        if ($time instanceof DateInterval) {
            $this->expires = (new DateTimeImmutable())->add($time);
            return $this;
        }
        throw new InvalidArgumentException('time must be null, int or DateInterval');
    }

    public function getExpires(): ?DateTimeInterface
    {
        return $this->expires;
    }
}
