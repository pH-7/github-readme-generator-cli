#!/usr/bin/env php

<?php

define('ROOT_DIR', __DIR__ . '/..');
define('APP_NAME', 'README File Generator');
define('APP_VERSION', '1.0.0')

require ROOT_DIR . '/vendor/autoload.php';

use Monolog\{
    Logger, Handler\StreamHandler
};
use PH7\PhpReadmeGeneratorFile\Command\Markdown\{
    Generator,
    Information
};
use Symfony\Component\Console\Application;

$logger = new Logger('ReadmeGenerator');
$logger->pushHandler(new StreamHandler("php://stdout", Logger::DEBUG));

$app = new Application(
    APP_NAME,
    APP_VERSION
);

$commands = [
    new Generator()
];

$app->addCommands($commands);

$app->run();
