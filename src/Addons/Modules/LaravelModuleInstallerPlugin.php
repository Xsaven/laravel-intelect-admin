<?php
namespace Lia\Addons\Modules;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class LaravelModuleInstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new LaravelModuleInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}