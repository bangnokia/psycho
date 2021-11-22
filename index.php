<?php

use Symfony\Component\Console\Output\ConsoleOutput;

include __DIR__ . '/vendor/autoload.php';

$arguments = getopt('', ['target:', 'code:', 'format:', 'mode:']);

$clockwerk = new BangNokia\Psycho\Clockwerk($arguments['mode'] ?? 'buffered');

$output = $clockwerk->bootstrapAt($arguments['target'] ?? '')->execute(base64_decode(trim($arguments['code'])));

$writer = new ConsoleOutput();

// Support format "raw" and "json"
$format = $arguments['format'] ?? 'raw';

if ($format === "raw") {
    $writer->writeln($output);
} else {
    // Not sure about meta but I'm think we don't need the meta here
    $writer->writeln(json_encode([
        'output' => $output,
        'meta' => []
    ]));
}

return 0;
