<?php

namespace Lia\Addons\Modules\Laravel;

use Lia\Addons\Modules\Json;
use Lia\Addons\Modules\Repository as BaseRepository;

class Repository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(...$args)
    {
        return new Module(...$args);
    }
}
