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

        $output = shell_exec($command);

        $this->assertEquals(base64_encode('=> "bar"'), trim($output));
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
        $output = $process->getOutput();

        $this->assertEquals(base64_encode('=> "hello tinker"'), trim($output));
    }
}
