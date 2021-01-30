<?php

include __DIR__.'/vendor/autoload.php';

$arguments = [];
foreach (array_slice($argv, 1) as  $line) {
    if (preg_match('/^--([^=]+)=(.*)/', $line, $match)) {
        $arguments[$match[1]] = $match[2];
    }
}

$clockwerk = new BelowCode\Psycho\Clockwerk();

$output = $clockwerk->bootstrapAt($arguments['target'])->execute($arguments['code']);

$writer = new \Symfony\Component\Console\Output\ConsoleOutput();
$writer->writeln($output);

return 0;

