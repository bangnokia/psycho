<?php

namespace BelowCode\Psycho\Tests;

use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testCallFromOtherProcess()
    {
        $entry = __DIR__.'/../index.php';
        $target = __DIR__.'/fixtures/foo';
        $phpCode = 'foo()';

        $command = "php $entry --target=$target --code=\"$phpCode\"";

        $output = shell_exec($command);

        $this->assertEquals('=> "bar"', trim($output));
    }
}