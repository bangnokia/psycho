<?php

include __DIR__.'/vendor/autoload.php';

$arguments = getopt('', ['target:', 'code:']);

$clockwerk = new BangNokia\Psycho\Clockwerk();

$output = $clockwerk->bootstrapAt($arguments['target'])->execute(base64_decode(trim($arguments['code'])));

$writer = new \Symfony\Component\Console\Output\ConsoleOutput();
$writer->writeln($output);

return 0;

