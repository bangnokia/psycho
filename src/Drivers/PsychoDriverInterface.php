<?php

namespace BelowCode\Psycho\Drivers;

interface PsychoDriverInterface
{
    public function deployable(string $project): bool;

    /**
     * Autobots roll out!
     *
     * @param  string  $project
     */
    public function rollOut(string $project): void;
}