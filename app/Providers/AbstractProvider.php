<?php
declare(strict_types=1);

namespace App\Providers;

use App\Container;

abstract class AbstractProvider
{
    public function __construct(protected Container $container)
    {
    }

    public abstract function abstractClass(): string;

    public abstract function factory();
}