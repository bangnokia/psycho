<?php

namespace BangNokia\Psycho;

use BangNokia\Psycho\Drivers\LaravelDriver;
use BangNokia\Psycho\Drivers\PlanProjectDriver;
use BangNokia\Psycho\Drivers\PsychoDriver;

class Sherlock
{
    /**
     * @var string[]
     */
    protected $drivers = [
        LaravelDriver::class,
    ];

    /**
     * @param  string  $subject
     *
     * @return PsychoDriver|null
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
