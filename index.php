<?php

include __DIR__.'/vendor/autoload.php';

$clockwerk = new BelowCode\Psycho\Clockwerk();

$code = <<<'EOD'
$name = 'daudau';
$name .= ' test'
EOD;

$target = '/Users/daudau/Code/inventory';

$output = $clockwerk->bootstrapAt($target)->execute($code);

$writer = new \Symfony\Component\Console\Output\ConsoleOutput();
$writer->writeln($output);





