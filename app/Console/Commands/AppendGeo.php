<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Restaurant;

class AppendGeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:geo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        if (!is_null(getenv('GOOGLE_MAPS_KEY')) && (env('APP_ENV') === 'production' || env('GOOGLE_MAPS_API_TEST'))) {
            Restaurant::whereNull('positions')->get()->each(function ($restaurant) {
                $restaurant->positions = Restaurant::a2p($restaurant->address);
                $restaurant->save();
            });
        }
    }
}
