<?php

namespace BelowCode\Psycho;

use BelowCode\Psycho\Drivers\LaravelPsychoPsychoDriver;
use BelowCode\Psycho\Drivers\PlanPsychoDriver;
use BelowCode\Psycho\Drivers\PsychoDriverInterface;

class Sherlock
{
    protected array $drivers = [
        LaravelPsychoPsychoDriver::class,
    ];

    /**
     * @param  string  $subject
     *
     * @return PsychoDriverInterface|null
     */
    public function detect(string $subject)
    {
        foreach ($this->drivers as $driverClass) {
            $driver = new $driverClass();

            if ($driver->deployable($subject)) {
                return $driver;
            }
        }

        return new PlanPsychoDriver();
    }
}