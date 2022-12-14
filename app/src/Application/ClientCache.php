<?php

namespace App\src\Application;

use Illuminate\Contracts\Cache\Repository;

/**
 * @method rates(\DateTime $date)
 */
class ClientCache
{
    public function __construct(
        private CurrencyClientInterface $client,
        private Repository $cache
    ){}

    public function __call(string $name, array $arguments)
    {
        return $this->cache->rememberForever(
            $this->cacheKey($name, $arguments),
            fn() => $this->client->{$name}(...$arguments)
        );
    }

    private function cacheKey(string $method, array $arguments): string
    {
        return $this->client::class.'.'.
            $method.'.'.
            json_encode($arguments);
    }
}
