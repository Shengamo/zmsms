<?php

namespace Orchestra\Canvas\Console;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns\CodeGenerator;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Foundation/Console/RuleMakeCommand.php
 */
#[AsCommand(name: 'make:rule', description: 'Create a new validation rule')]
class RuleMakeCommand extends \Illuminate\Foundation\Console\RuleMakeCommand
{
    use CodeGenerator;
    use UsesGeneratorOverrides;

    /**
     * Create a new creator command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addGeneratorPresetOptions();
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    #[\Override]
    public function handle()
    {
        return $this->generateCode() ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    #[\Override]
    protected function getPath($name)
    {
        return $this->getPathUsingCanvas($name);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    #[\Override]
    protected function rootNamespace()
    {
        return $this->rootNamespaceUsingCanvas();
    }
}
