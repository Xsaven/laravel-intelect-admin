<?php

namespace Lia\Addons\Modules\Process;

use Lia\Addons\Modules\Contracts\RunableInterface;
use Lia\Addons\Modules\Repository;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     *
     * @var \Lia\Addons\Modules\Repository
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Lia\Addons\Modules\Repository $module
     */
    public function __construct(Repository $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}
