<?php

namespace Lia\Addons\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Lia\Addons\Modules\Commands\CommandMakeCommand;
use Lia\Addons\Modules\Commands\ControllerMakeCommand;
use Lia\Addons\Modules\Commands\DisableCommand;
use Lia\Addons\Modules\Commands\DumpCommand;
use Lia\Addons\Modules\Commands\EnableCommand;
use Lia\Addons\Modules\Commands\EventMakeCommand;
use Lia\Addons\Modules\Commands\FactoryMakeCommand;
use Lia\Addons\Modules\Commands\InstallCommand;
use Lia\Addons\Modules\Commands\JobMakeCommand;
use Lia\Addons\Modules\Commands\ListCommand;
use Lia\Addons\Modules\Commands\ListenerMakeCommand;
use Lia\Addons\Modules\Commands\MailMakeCommand;
use Lia\Addons\Modules\Commands\MiddlewareMakeCommand;
use Lia\Addons\Modules\Commands\MigrateCommand;
use Lia\Addons\Modules\Commands\MigrateRefreshCommand;
use Lia\Addons\Modules\Commands\MigrateResetCommand;
use Lia\Addons\Modules\Commands\MigrateRollbackCommand;
use Lia\Addons\Modules\Commands\MigrateStatusCommand;
use Lia\Addons\Modules\Commands\MigrationMakeCommand;
use Lia\Addons\Modules\Commands\ModelMakeCommand;
use Lia\Addons\Modules\Commands\ModuleMakeCommand;
use Lia\Addons\Modules\Commands\NotificationMakeCommand;
use Lia\Addons\Modules\Commands\PolicyMakeCommand;
use Lia\Addons\Modules\Commands\ProviderMakeCommand;
use Lia\Addons\Modules\Commands\PublishCommand;
use Lia\Addons\Modules\Commands\PublishConfigurationCommand;
use Lia\Addons\Modules\Commands\PublishMigrationCommand;
use Lia\Addons\Modules\Commands\PublishTranslationCommand;
use Lia\Addons\Modules\Commands\RequestMakeCommand;
use Lia\Addons\Modules\Commands\ResourceMakeCommand;
use Lia\Addons\Modules\Commands\RouteProviderMakeCommand;
use Lia\Addons\Modules\Commands\RuleMakeCommand;
use Lia\Addons\Modules\Commands\SeedCommand;
use Lia\Addons\Modules\Commands\SeedMakeCommand;
use Lia\Addons\Modules\Commands\SetupCommand;
use Lia\Addons\Modules\Commands\TestMakeCommand;
use Lia\Addons\Modules\Commands\UnUseCommand;
use Lia\Addons\Modules\Commands\UpdateCommand;
use Lia\Addons\Modules\Commands\UseCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * The available commands
     *
     * @var array
     */
    protected $commands = [
        CommandMakeCommand::class,
        ControllerMakeCommand::class,
        DisableCommand::class,
        DumpCommand::class,
        EnableCommand::class,
        EventMakeCommand::class,
        JobMakeCommand::class,
        ListenerMakeCommand::class,
        MailMakeCommand::class,
        MiddlewareMakeCommand::class,
        NotificationMakeCommand::class,
        ProviderMakeCommand::class,
        RouteProviderMakeCommand::class,
        InstallCommand::class,
        ListCommand::class,
        ModuleMakeCommand::class,
        FactoryMakeCommand::class,
        PolicyMakeCommand::class,
        RequestMakeCommand::class,
        RuleMakeCommand::class,
        MigrateCommand::class,
        MigrateRefreshCommand::class,
        MigrateResetCommand::class,
        MigrateRollbackCommand::class,
        MigrateStatusCommand::class,
        MigrationMakeCommand::class,
        ModelMakeCommand::class,
        PublishCommand::class,
        PublishConfigurationCommand::class,
        PublishMigrationCommand::class,
        PublishTranslationCommand::class,
        SeedCommand::class,
        SeedMakeCommand::class,
        SetupCommand::class,
        UnUseCommand::class,
        UpdateCommand::class,
        UseCommand::class,
        ResourceMakeCommand::class,
        TestMakeCommand::class,
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->commands;

        return $provides;
    }
}
