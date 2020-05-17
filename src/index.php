<?php
/**
 * Date: 11.04.2020
 * Time: 13:21
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use kradwhite\language\command\ConfigCommand;
use kradwhite\language\command\TextsCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->addCommands([
    new ConfigCommand(),
    new TextsCommand(),
]);

$application->run();