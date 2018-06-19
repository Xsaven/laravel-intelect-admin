<?php

namespace Lia\Console\System;

use Lia\Admin;
use Illuminate\Console\Command;

class EnvCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lia:env {field} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '.env Editor (lia:env {field} {value})';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $field = $this->argument('field');
        $value = $this->argument('value');

        if(env($field))

            file_put_contents($this->laravel->environmentFilePath(), preg_replace(
                $this->keyReplacementPattern($field, env($field)),
                $field.'='.(strpos($value, ' ') !== false || strpos($value, '${') !== false ? "\"{$value}\"" : $value),
                file_get_contents($this->laravel->environmentFilePath())
            ));

        else

            file_put_contents($this->laravel->environmentFilePath(), file_get_contents($this->laravel->environmentFilePath())."\n".
                $field.'='.(strpos($value, ' ') !== false || strpos($value, '${') !== false ? "\"{$value}\"" : $value));


        $this->info($this->laravel->environmentFilePath() . ' - Updated!');
    }

    protected function keyReplacementPattern($field, $value)
    {
        $escaped = preg_quote('='.$value, '/');
        $escaped_1 = preg_quote('="'.$value.'"', '/');
        $escaped_2 = preg_quote("='".$value."'", '/');

        return ["/^{$field}{$escaped}/m", "/^{$field}{$escaped_1}/m", "/^{$field}{$escaped_2}/m"];
    }
}
