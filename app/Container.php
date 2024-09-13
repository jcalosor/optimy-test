<?php
declare(strict_types=1);

namespace App;

class Container
{
    private array $bindings;

    public function set($abstract, $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function get($abstract)
    {
        return $this->bindings[$abstract];
    }
}