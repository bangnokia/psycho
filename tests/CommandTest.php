<?php

namespace BangNokia\Psycho\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class CommandTest extends TestCase
{
    public function testCallFromOtherProcess()
    {
        $entry = __DIR__.'/../index.php';
        $target = __DIR__.'/fixtures/foo';
        $phpCode = 'foo()';

        $command = "php $entry --target=$target --code=" . base64_encode($phpCode);

        $result = json_decode(shell_exec($command), true);

        $this->assertEquals('=> "bar"', $result['output']);
    }

    public function testCanPassMultipleLinesOfCode()
    {
        $entry = __DIR__.'/../index.php';
        $target = __DIR__;
        $phpCode = <<<'EOF'
$name = 'tinker';
$greeting = 'hello '.$name;
EOF;

        $process = new Process(['php', $entry, "--target=$target", "--code=" . base64_encode($phpCode)]);

        $process->run();

        $result = json_decode($process->getOutput(), true);

        $this->assertEquals('=> "hello tinker"', $result['output']);
    }
}
