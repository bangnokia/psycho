<?php

namespace BelowCode\Psycho;

use Psy\Configuration;
use Psy\ExecutionLoopClosure;
use Psy\Shell;
use Psy\VersionUpdater\Checker;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class Clockwerk
{
    protected OutputInterface $output;

    protected Shell $shell;

    protected Sherlock $sherlock;

    public function __construct()
    {
        $this->output = new BufferedOutput();
        $this->sherlock = new Sherlock();

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
        $this->sherlock->detect($target)->rollOut($target);

        return $this;
    }

    public function execute(string $phpCode): string
    {
        // result here is php variable
        $result = $this->shell->execute($phpCode);

        // here we write to output to get raw string after processed by presenter
        $this->shell->writeReturnValue($result);

        $output = $this->output->fetch();

        return $this->cleanOutput($output);
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