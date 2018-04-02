<?php

namespace Lia\Addons\Terminal\Console\Commands;

use function Couchbase\basicDecoderV1;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Lia\Addons\Terminal\Contracts\WebCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Help\Filemanager\PHPFileSystem;

class CD extends Command implements WebCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moving on the local system';

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
        $path = $this->argument('path');
        $path = str_replace('//', '/', $path);
        if($path=='/'){
            session(['cd_base_path' => base_path()]);
            $this->info(base_path());
            return true;
        }
        $root = session('cd_base_path');
        if(strrpos($path, "../")!==false){
            $clear = str_replace(base_path(),'',$root);
            $clear = str_replace("\\","",$clear);
            $clear = trim($clear, '/');
            $root = explode('/', $clear);
            foreach (explode('/', $path) as $pat) if($pat=='..') {array_pop($root);}
            $root = array_diff($root, array(''));
            $root = trim(implode('/', $root), '/');
            $root = base_path($root);
            $path = str_replace('../','',$path);
        }
        $path = trim($root, '/').($path && !empty($path)!=='/'?'/':'').$path;
        $path = str_replace('//', '/', $path);
        if(!is_dir($path)){
            $this->error("Path \"{$path}\" not found!");
            return true;
        }
        $path = str_replace("\\\\","\\", $path);
        session(['cd_base_path' => $path]);
        $this->info($path);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['path', InputArgument::REQUIRED, 'path'],
        ];
    }
}
