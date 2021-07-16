<?php

include __DIR__.'/vendor/autoload.php';

define('PSYCHO_VERSION', '0.1.0');

$arguments = getopt('', ['target:', 'code:']);

$clockwerk = new BangNokia\Psycho\Clockwerk();

$output = $clockwerk->bootstrapAt($arguments['target'])->execute(base64_decode(trim($arguments['code'])));

$writer = new \Symfony\Component\Console\Output\ConsoleOutput();
$writer->writeln(base64_encode($output));

return 0;

