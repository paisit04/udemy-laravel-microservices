<?php

namespace App\Console\Commands;

use \Services\UserService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Console\Command;

class UpdateRankingsCommand extends Command
{
    protected $signature = 'update:rankings';

    public function handle()
    {
        $users = collect((new UserService())->get('users'));
        $ambassadors = $users->filter(fn ($user) => $user['is_admin'] === 0);

        $bar = $this->output->createProgressBar($ambassadors->count());

        $bar->start();

        $ambassadors->each(function ($user) use ($bar) {
            $orders = Orders::where('user_id', $user->id)->get();
            $revenue = $orders->sum(fn (Order $order) => $order->total);
            Redis::zadd('rankings', (int)$revenue, $user->first_name . ' ' . $user->last_name);

            $bar->advance();
        });

        $bar->finish();
    }
}
