<?php

namespace BangNokia\Psycho;

use BangNokia\Psycho\Drivers\PsychoDriver;
use BangNokia\Psycho\Drivers\LaravelDriver;
use BangNokia\Psycho\Drivers\WordpressDriver;
use BangNokia\Psycho\Drivers\PlanProjectDriver;

class Sherlock
{
    /**
     * @var string[]
     */
    protected $drivers = [
        LaravelDriver::class,
        WordpressDriver::class,
    ];

    /**
     * @param  string  $subject
     *
     * @return PsychoDriver
     */
    public function detect(string $subject)
    {
        foreach ($this->drivers as $driverClass) {
            $driver = new $driverClass();

            if ($driver->deployable($subject)) {
                return $driver;
            }
        }

        return new PlanProjectDriver();
    }
}
