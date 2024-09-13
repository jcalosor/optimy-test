<?php
declare(strict_types=1);

$containerConfig = require_once __DIR__ . '/config/container.php';

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

use App\Container;
use App\Utils\DB;

$container = new Container();

// Set the global DB properties
$container->set(
    DB::class,
    new DB(
        new \PDO(
            \sprintf('mysql:dbname=%s;host=%s', $_ENV['DB_NAME'], $_ENV['DB_HOST']),
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"')
        )
    )
);

// Resolve all the classes specified in the config.
foreach ($containerConfig as $providerClass) {
    /** @var \App\Providers\AbstractProvider $provider */
    $provider = new $providerClass($container);
    $container->set($provider->abstractClass(), $provider->factory());
}

return $container;