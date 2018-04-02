<?php

namespace Lia\Console;

use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lia:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the LaraveIDE-admin package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure to uninstall LaravelIDE-admin?')) {
            return;
        }

        $this->removeFilesAndDirectories();

        $this->line('<info>Uninstalling LaravelIDE-admin!</info>');
    }

    /**
     * Remove files and directories.
     *
     * @return void
     */
    protected function removeFilesAndDirectories()
    {
        $this->laravel['files']->deleteDirectory(config('lia.directory'));
        $this->laravel['files']->deleteDirectory(public_path('vendor/lia/'));
        $this->laravel['files']->delete(config_path('lia.php'));
        $this->laravel['files']->delete(config_path('terminal.php'));
    }
}
