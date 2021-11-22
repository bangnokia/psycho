<?php

namespace BangNokia\Psycho;

use Psy\Shell;

/**
 * @param string $class
 * @return string
 */
function class_basename($class)
{
    $class = is_object($class) ? get_class($class) : $class;

    return basename(str_replace('\\', '/', $class));
}

class ClassAliasAutoloader
{
    /**
     * The shell instance.
     *
     * @var \Psy\Shell
     */
    protected $shell;

    /**
     * All of the discovered classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Path to the vendor directory.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * Register a new alias loader instance.
     *
     * @param  \Psy\Shell  $shell
     * @param  string  $classMapPath
     * @return static
     */
    public static function register(Shell $shell, $classMapPath)
    {
        $loader = new static($shell, $classMapPath);

        spl_autoload_register([$loader, 'aliasClass']);

        return $loader;
    }

    /**
     * Create a new alias loader instance.
     *
     * @param  \Psy\Shell  $shell
     * @param  string  $classMapPath
     * @param  array  $includedAliases
     * @param  array  $excludedAliases
     * @return void
     */
    public function __construct(Shell $shell, $classMapPath)
    {
        $this->shell = $shell;
        $this->vendorPath = dirname(dirname($classMapPath));

        $classes = require $classMapPath;

        foreach ($classes as $class => $path) {
            if (! $this->isAliasable($class, $path)) {
                continue;
            }

            $name = class_basename($class);

            if (! isset($this->classes[$name])) {
                $this->classes[$name] = $class;
            }
        }
    }

    /**
     * Find the closest class by name.
     *
     * @param  string  $class
     * @return void
     */
    public function aliasClass($class)
    {
        if (mb_strpos($class, '\\') !== false) {
            return;
        }

        $fullName = $this->classes[$class] ?? false;

        if ($fullName) {
            $this->shell->writeStdout("[!] Aliasing '{$class}' to '{$fullName}' for this Tinker session.\n");

            class_alias($fullName, $class);
        }
    }

    /**
     * Unregister the alias loader instance.
     *
     * @return void
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, 'aliasClass']);
    }

    /**
     * Handle the destruction of the instance.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->unregister();
    }

    /**
     * Whether a class may be aliased.
     *
     * @param  string  $class
     * @param  string  $path
     */
    public function isAliasable($class, $path)
    {
        if (! mb_strpos($class, '\\')) {
            return false;
        }

        if (strncmp($class, $this->vendorPath, strlen($this->vendorPath)) === 0) {
            return false;
        }

        return true;
    }
}
