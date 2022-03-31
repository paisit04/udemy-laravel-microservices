<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Order;
use App\Jobs\OrderCompleted;

class ProduceCommand extends Command
{
    protected $signature = 'produce';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order = Order::find(1);
        $array = $order->toArray();
        $array['ambassador_revenue'] = $order->ambassador_revenue;
        OrderCompleted::dispatch($array)->onQueue("email_topic");
        return 0;
    }
}
