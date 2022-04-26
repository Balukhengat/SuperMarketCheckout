<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Supermarket;

class ScanProductsToCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
     * @return int
     */
    public function handle()
    {
        $supermarketObj = new Supermarket();
        $supermarketObj->scanItems('D',6);
        $supermarketObj->scanItems('A',4);
        $total = $supermarketObj->checkout();
        dd($total);
    }
}
