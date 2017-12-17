<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IntegrityFood extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integrity:foods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Integrity verification. Remove foods that not having photos';

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
        $foods = \App\Food::has('photos', '=', 0)->get();
        $foods->each(function ($food) {
            $food->delete();
        });
    }
}
