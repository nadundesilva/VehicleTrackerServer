<?php

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DropSchemaDoctrineCommand;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__ . '/AppKernel.php';

/*
 * Creating a test kernel and assigning the kernel to a application
 */
$kernel = new AppKernel('test', true);
$kernel->boot();
$application = new Application($kernel);

/*
 * For creating a database for testing
 *
 * Assumes that the "web/test_db_sqlite" file exists
 * "web/test_db_sqlite" file contains the database
 * If "web/test_db_sqlite" file does not exist run "php bin/console doctrine:database:create" from the root directory
 *
 * Only used in test environment
 */

/*
 * Drop the current schema in the database
 */
$command = new DropSchemaDoctrineCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'doctrine:schema:drop',
    '--force' => true,
));
$command->run($input, new ConsoleOutput());

/*
 * Create a new schema in the database
 */
$command = new CreateSchemaDoctrineCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'doctrine:schema:create',
));
$command->run($input, new ConsoleOutput());

/*
 * Insert fixtures to the database
 */
$command = new LoadDataFixturesDoctrineCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'doctrine:fixtures:load',
    '--append' => true,
));
$command->run($input, new ConsoleOutput());
