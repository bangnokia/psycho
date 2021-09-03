<?php

namespace BangNokia\Psycho\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class CommandTest extends TestCase
{
    public function testCallFromOtherProcessWithJsonOutput()
    {
        $entry = __DIR__.'/../index.php';
        $target = __DIR__.'/fixtures/foo';
        $phpCode = base64_encode('foo()');

        $command = "php {$entry} --target={$target} --code={$phpCode} --format=json";

        $output = shell_exec($command);

        $result = json_decode($output, true);

        $this->assertEquals('=> "bar"', $result['output']);
    }

    public function testCallFromOtherProcessWithRawOutput()
    {
        $entry = __DIR__.'/../index.php';
        $target = __DIR__.'/fixtures/foo';
        $phpCode = base64_encode('foo()');

        $command = "php {$entry} --target={$target} --code={$phpCode}";

        $output = shell_exec($command);

        $this->assertEquals('=> "bar"', trim($output));
    }

    public function testCanPassMultipleLinesOfCode()
    {
        $entry = __DIR__.'/../index.php';
        $target = __DIR__;
        $phpCode = base64_encode(<<<'EOF'
$name = 'tinker';
$greeting = 'hello '.$name;
EOF);

        $process = new Process(['php', $entry, "--target=$target", "--code=$phpCode", "--format=json"]);

        $process->run();

        $result = json_decode($process->getOutput(), true);

        $this->assertEquals('=> "hello tinker"', $result['output']);
    }
}
