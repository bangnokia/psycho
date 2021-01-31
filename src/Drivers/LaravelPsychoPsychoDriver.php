<?php


namespace BelowCode\Psycho\Drivers;


class LaravelPsychoPsychoDriver implements PsychoDriverInterface
{
    public function deployable(string $project): bool
    {
        return file_exists($project.'/artisan') && file_exists($project.'/public/index.php');
    }

    public function rollOut(string $project): void
    {
        require_once $project.'/vendor/autoload.php';

        $app = require $project.'/bootstrap/app.php';

        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

        $kernel->bootstrap();
    }
}