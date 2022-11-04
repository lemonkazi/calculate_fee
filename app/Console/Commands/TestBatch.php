<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Log;

class TestBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:test';
 
    /**
     * The console command description.
    * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->info(sprintf('Importing file "%s"', $file), $cmd);
        //$this->info(sprintf('Importing file '));
        \Log::info(get_class($this).': It worked!');
    }
}
