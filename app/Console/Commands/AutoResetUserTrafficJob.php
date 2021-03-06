<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\Config;
use App\Http\Models\User;
use Log;

class AutoResetUserTrafficJob extends Command
{
    protected $signature = 'command:autoResetUserTrafficJob';
    protected $description = '自动重置账号的流量';

    protected static $config;

    public function __construct()
    {
        parent::__construct();

        $config = Config::get();
        $data = [];
        foreach ($config as $vo) {
            $data[$vo->name] = $vo->value;
        }

        self::$config = $data;
    }

    public function handle()
    {
        if (self::$config['reset_traffic']) {
            $user_ids = User::where('pay_way', '<>', 0)->select(['id'])->get();
            User::whereIn('id', $user_ids)->update(['u' => 0, 'd' => 0]);
        }

        Log::info('定时任务：' . $this->description);
    }
}
