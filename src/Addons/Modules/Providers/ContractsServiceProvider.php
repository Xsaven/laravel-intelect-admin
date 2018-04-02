<?php

namespace Lia\Addons\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Lia\Addons\Modules\Contracts\RepositoryInterface;
use Lia\Addons\Modules\Laravel\Repository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
    }
}
