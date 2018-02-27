#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use RevolutionConsole\Command\DefaultCommand;

// TODO: Read in CSV
// TODO: Provide error feedback if file not found
// TODO: Process/calculate data
// TODO: Output CSV
// TODO: Provide error feedback if outputting fails
// TODO: Implement the progress bar for while data is loa processing
// FUTURE TODO: Add results sort command line option

$command = new DefaultCommand();
$app = new Application("Revolution PLU CSV Calculator", "1.0");
$app->add($command);
$app->setDefaultCommand($command->getName(), true);
$app->run();