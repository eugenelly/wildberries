<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Support\Facades\Artisan;

class Serve extends ServeCommand
{
    /**
     * Execute the console command.
     *
     * @return int
     *
     * @throws \Exception
     */
    public function handle()
    {
        parent::handle();

//        Artisan::call(
//            'wildberries:upsert '
//            . config('services')['wildberries']['key']
//            . ' 2022-06-01'
//        );
    }
}
