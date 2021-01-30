<?php

namespace BelowCode\Psycho;

use Psy\Configuration;
use Psy\ExecutionLoopClosure;
use Psy\Shell;
use Psy\VersionUpdater\Checker;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;

class Clockwerk
{
    protected Shell $shell;

    protected Output $output;

    protected string $target;

    public function __construct()
    {
        $this->output = new BufferedOutput();

        $this->makeShell()
            ->setShellOutput($this->output);
    }

    protected function makeShell(): self
    {
        $config = new Configuration([
            'updateCheck' => Checker::NEVER
        ]);

        $this->shell = new Shell($config);

        return $this;
    }

    protected function setShellOutput(Output $output): self
    {
        $this->shell->setOutput($output);

        return $this;
    }

    /**
     * Laravel bootstrap
     *
     * @param  string  $target
     * @return Clockwerk
     */
    public function bootstrapAt(string $target): self
    {
        $this->target = $target;

        // laravel bootstrap
        require $target.'/vendor/autoload.php';
        $app = require_once $target.'/bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $this;
    }

    public function execute(string $phpCode): string
    {
        $this->shell->addInput($phpCode);

        $closure = new ExecutionLoopClosure($this->shell);

        $closure->execute();

        $output = $this->output->fetch();
        $output = $this->cleanOutput($output);

        return $output;
    }

    /**
     * Copy from spatie/web-tinker
     *
     * @param  string  $output
     * @return string
     */
    protected function cleanOutput(string $output): string
    {
        $output = preg_replace('/(?s)(<aside.*?<\/aside>)|Exit:  Ctrl\+D/ms', '$2', $output);

        return trim($output);
    }
}