<?php

include __DIR__.'/vendor/autoload.php';

define('PSYCHO_VERSION', '0.1.0');

$arguments = getopt('', ['target:', 'code:']);

$clockwerk = new BangNokia\Psycho\Clockwerk();

$output = $clockwerk->bootstrapAt($arguments['target'])->execute(trim($arguments['code']));

$writer = new \Symfony\Component\Console\Output\ConsoleOutput();
$writer->writeln($output);

return 0;

