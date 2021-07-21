<?php

use Symfony\Component\Console\Output\ConsoleOutput;

include __DIR__.'/vendor/autoload.php';

const PSYCHO_VERSION = '0.1.0';

$arguments = getopt('', ['target:', 'code:']);

$clockwerk = new BangNokia\Psycho\Clockwerk();

$output = $clockwerk->bootstrapAt($arguments['target'] ?? '')->execute(base64_decode(trim($arguments['code'])));

$writer = new ConsoleOutput();

$writer->writeln(json_encode([
    'output' => $output,
    'meta' => []
]));

return 0;

