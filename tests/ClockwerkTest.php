<?php

namespace BelowCode\Psycho\Tests;

use BelowCode\Psycho\Clockwerk;

class ClockwerkTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testItLoadDefaultVendor()
    {
        $target = __DIR__.'/fixtures';

        $clockwerk=  new Clockwerk();
        $phpCode = 'foo()';

        $output = $clockwerk->bootstrapAt($target)->execute($phpCode);

        $this->assertStringContainsString('bar', $output);
    }
}