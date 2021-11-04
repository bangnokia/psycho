<?php

use Symfony\Component\Console\Output\ConsoleOutput;

include __DIR__.'/vendor/autoload.php';

const PSYCHO_VERSION = '0.1.0';

$arguments = getopt('', ['target:', 'code:', 'format:', 'mode:']);

$clockwerk = new BangNokia\Psycho\Clockwerk(($arguments['mode'] ?? 'sync') === 'realtime');

// testing
//$arguments['code'] = base64_encode('foreach (range(1, 3) as $i) {
//    echo $i.PHP_EOL;
//    sleep(1);
//}');

$output = $clockwerk->bootstrapAt($arguments['target'] ?? '')->execute(base64_decode(trim($arguments['code'])));

$writer = new ConsoleOutput();

// Support format "raw" and "json"
$format = $arguments['format'] ?? 'raw';

if ($format === "raw") {
    $writer->writeln($output);
} else {
    // Not sure about meta but I'm think i don't need the meta here
    $writer->writeln(json_encode([
        'output' => $output,
        'meta' => []
    ]));
}
return 0;

