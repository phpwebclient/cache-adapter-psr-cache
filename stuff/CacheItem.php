<?php

declare(strict_types=1);

namespace Stuff\Webclient\Cache\Adapter\PsrCache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    private string $key;
    private mixed $value;
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

    public function get(): mixed
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return $this->value !== null;
    }

    public function set(mixed $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function expiresAt(?DateTimeInterface $expiration): static
    {
        if (is_null($expiration)) {
            $this->expires = null;
            return $this;
        }
        $this->expires = $expiration;
        return $this;
    }

    public function expiresAfter(int|DateInterval|null $time): static
    {
        if (is_null($time)) {
            $this->expires = null;
            return $this;
        }
        if (is_int($time)) {
            $this->expires = (new DateTimeImmutable())->modify('+' . $time . ' seconds');
            return $this;
        }
        $this->expires = (new DateTimeImmutable())->add($time);
        return $this;
    }

    public function getExpires(): ?DateTimeInterface
    {
        return $this->expires;
    }
}
