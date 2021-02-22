<?php

namespace BangNokia\Psycho\Tests;

use BangNokia\Psycho\Drivers\LaravelDriver;
use BangNokia\Psycho\Drivers\PlanProjectDriver;
use BangNokia\Psycho\Sherlock;
use PHPUnit\Framework\TestCase;

class SherlockTest extends TestCase
{
    /**
     * @var Sherlock
     */
    protected $sherlock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sherlock = new Sherlock();
    }

    public function testItCanDetectLaravelDriver()
    {
        $subject = $this->sherlock->detect(__DIR__.'/fixtures/drivers/laravel');

        $this->assertInstanceOf(LaravelDriver::class, $subject);
    }

    public function testItCanFallbackToPlanDriverIfCanNotDetectAnyThing()
    {
        $subject = $this->sherlock->detect(__DIR__.'/fixtures/drivers/dummy');

        $this->assertInstanceOf(PlanProjectDriver::class, $subject);
    }
}
