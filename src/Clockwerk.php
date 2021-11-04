<?php

namespace BangNokia\Psycho;

use Psy\Configuration;
use Psy\Shell;
use Psy\VersionUpdater\Checker;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class Clockwerk
{
    /**
     * @var Shell
     */
    protected $shell;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Sherlock
     */
    protected $sherlock;

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * @var array
     */
    protected $casters = [];

    /** @var bool  */
    protected $realtimeOutput = false;

    public function __construct($realtimeOutput = false)
    {
        $this->realtimeOutput = $realtimeOutput;
        $this->output = $this->realtimeOutput ? new StreamOutput(fopen('php://stdout', 'w')) : new BufferedOutput();
        $this->sherlock = new Sherlock();
    }

    protected function makeShell()
    {
        $config = new Configuration([
            'updateCheck' => Checker::NEVER,
            'configFile'  => null
        ]);
        $config->setHistoryFile(defined('PHP_WINDOWS_VERSION_BUILD') ? 'null' : '/dev/null');
        $config->getPresenter()->addCasters($this->casters);

        $this->shell = new Shell($config);
        $this->shell->setOutput($this->output);

        if (file_exists($composerClassMap = $this->targetPath.'/vendor/composer/autoload_classmap.php')) {
            ClassAliasAutoloader::register($this->shell, $composerClassMap);
        }

        return $this;
    }

    /**
     * @param  Output  $output
     * @return $this
     */
    protected function setShellOutput($output)
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
    public function bootstrapAt($target)
    {
        $this->targetPath = $target;

        $driver = $this->sherlock->detect($this->targetPath);

        $driver->rollOut($this->targetPath);

        $this->casters = $driver->casters();

        $this->makeShell();

        $this->teleportToTargetDirectory();

        return $this;
    }

    protected function teleportToTargetDirectory() {
        if ($this->targetPath) {
            chdir($this->targetPath);
        }

        return $this;
    }

    /**
     * @param  string  $phpCode
     * @return string
     */
    public function execute($phpCode)
    {
        // result here is php variable
        $result = $this->shell->execute($this->removeComments($phpCode));

        // here we write to output to get raw string after processed by presenter
        $this->shell->writeReturnValue($result);

        if  (!$this->realtimeOutput) {
            $output = $this->output->fetch();

            return $this->cleanOutput($output);
        }

//        return null;
    }

    /**
     * @author spaties/laravel-web-tinker
     * @param  string  $code
     * @return string
     */
    public function removeComments($code)
    {
        $tokens = token_get_all("<?php\n".$code.'?>');

        return array_reduce($tokens, function ($carry, $token) {
            if (is_string($token)) {
                return $carry.$token;
            }

            $text = $this->ignoreCommentsAndPhpTags($token);

            return $carry.$text;
        }, '');
    }

    /**
     * @author spaties/laravel-web-tinker
     * @param  array  $token
     * @return mixed|string
     */
    protected function ignoreCommentsAndPhpTags($token)
    {
        [$id, $text] = $token;

        if ($id === T_COMMENT) {
            return '';
        }
        if ($id === T_DOC_COMMENT) {
            return '';
        }
        if ($id === T_OPEN_TAG) {
            return '';
        }
        if ($id === T_CLOSE_TAG) {
            return '';
        }

        return $text;
    }

    /**
     * @author spaties/laravel-web-tinker
     * @param  string  $output
     * @return string
     */
    protected function cleanOutput($output)
    {
        $output = preg_replace('/(?s)(<aside.*?<\/aside>)|Exit:  Ctrl\+D/ms', '$2', $output);

        return trim($output);
    }
}
