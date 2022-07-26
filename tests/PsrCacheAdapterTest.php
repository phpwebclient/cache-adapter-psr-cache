<?php

declare(strict_types=1);

namespace Tests\Webclient\Cache\Adapter\PsrCache;

use DateInterval;
use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use Stuff\Webclient\Cache\Adapter\PsrCache\CacheItem;
use Stuff\Webclient\Cache\Adapter\PsrCache\MemoryCacheItemPool;
use Webclient\Cache\Adapter\PsrCache\PsrCacheAdapter;

final class PsrCacheAdapterTest extends TestCase
{
    public function testGet(): void
    {
        $psrCache = new MemoryCacheItemPool();
        $adapter = new PsrCacheAdapter($psrCache);

        $item1 = new CacheItem('get1', 'value1');
        $item1->expiresAfter(5);
        $psrCache->save($item1);
        self::assertSame('value1', $adapter->get('get1'));

        $item2 = new CacheItem('get2', 'value2');
        $item2->expiresAfter(0);
        $psrCache->save($item2);
        self::assertSame(null, $adapter->get('get2'));

        $item3 = new CacheItem('get3', 'value3');
        $item3->expiresAfter(-1);
        $psrCache->save($item3);
        self::assertSame(null, $adapter->get('get3'));

        $item4 = new CacheItem('get4', 'value4');
        $item4->expiresAfter(new DateInterval('PT1H'));
        $psrCache->save($item4);
        self::assertSame('value4', $adapter->get('get4'));

        $interval = new DateInterval('PT1H');
        $interval->invert = 1;
        $item5 = new CacheItem('get5', 'value5');
        $item5->expiresAfter($interval);
        $psrCache->save($item5);
        self::assertSame(null, $adapter->get('get5'));

        $item6 = new CacheItem('get6', 'value6');
        $item6->expiresAfter(null);
        $psrCache->save($item6);
        self::assertSame('value6', $adapter->get('get6'));

        $item7 = new CacheItem('get7', ['value7']);
        $item7->expiresAfter(null);
        $psrCache->save($item7);
        self::assertSame(null, $adapter->get('get7'));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSet(): void
    {
        $psrCache = new MemoryCacheItemPool();
        $adapter = new PsrCacheAdapter($psrCache);

        $adapter->set('set1', 'value1', 5);
        self::assertSame('value1', $psrCache->getItem('set1')->get());

        $adapter->set('set2', 'value2', 0);
        self::assertSame(null, $psrCache->getItem('set2')->get());

        $adapter->set('set3', 'value3', -1);
        self::assertSame(null, $psrCache->getItem('set3')->get());

        $adapter->set('set4', 'value4');
        self::assertSame('value4', $psrCache->getItem('set4')->get());
    }
}
