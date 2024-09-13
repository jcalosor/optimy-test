<?php
declare(strict_types=1);

namespace Tests;

use Closure;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    /** @var \Faker\Generator */
    private $faker;
    
    /**
     * Get faker instance.
     *
     * @return \Faker\Generator
     */
    protected function getFaker(): Generator
    {
        if ($this->faker !== null) {
            return $this->faker;
        }

        return $this->faker = FakerFactory::create();
    }

    /**
     * Create mock for given class and set expectations using given closure.
     *
     * @param mixed $class
     * @param \Closure|null $setExpectations
     *
     * @return \Mockery\MockInterface
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery)
     */
    protected function mock($class, ?Closure $setExpectations = null): MockInterface
    {
        $mock = \Mockery::mock($class);

        if (\is_string($class) === false) {
            return $mock;
        }

        // If no expectations, early return
        if ($setExpectations === null) {
            return $mock;
        }

        // Pass mock to closure to set expectations
        $setExpectations($mock);

        return $mock;
    }
}