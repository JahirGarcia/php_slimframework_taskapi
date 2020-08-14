<?php

declare(strict_types=1);

use App\Domain\Tasks\TaskRepository;
use App\Infrastructure\Persistence\Tasks\MySqlTaskRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
  // Here we map our UserRepository interface to its in memory implementation
  $containerBuilder->addDefinitions([
    TaskRepository::class => \DI\autowire(MySqlTaskRepository::class),
  ]);
};
