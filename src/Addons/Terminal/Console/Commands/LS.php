<?php

namespace Lia\Addons\Terminal\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Lia\Addons\Terminal\Contracts\WebCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Help\Filemanager\PHPFileSystem;

class LS extends Command implements WebCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Current folder contents';

    /**
     * $connection.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $cd_connector;

    public function __construct()
    {
        parent::__construct();

        $this->cd_connector = new PHPFileSystem(session('cd_base_path'));
    }

    /**
     * Handle the command.
     *
     * @throws \InvalidArgumentException
     */
    public function handle()
    {
        $path = $this->cd_connector->ls("/", false, "branch")['data'];
        foreach ($path as $p){
            if($p['type']=='folder'){
                $this->info($p['value']);
            }else{
                $this->line($p['value']);
            }
        }
    }
}
































