<?php

namespace BangNokia\Psycho\Drivers;

class WordpressDriver extends PsychoDriver
{
    public function deployable(string $project): bool
    {
        return file_exists($project.'/wp-load.php');
    }

    public function rollOut(string $project): void
    {
        require $project.'/wp-load.php';
    }
}
