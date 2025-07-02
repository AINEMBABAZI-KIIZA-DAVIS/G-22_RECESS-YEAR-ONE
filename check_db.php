<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware\Contracts\Console\Kernel::class);

$response = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArrayInput([
        'command' => 'db:show',
        '--json' => true,
    ]),
    new Symfony\Component\Console\Output\ConsoleOutput()
);

$kernel->terminate($input, $response);
