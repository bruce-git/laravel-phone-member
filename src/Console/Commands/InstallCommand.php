<?php

namespace Bruce\LaravelShopPhoneMember\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    // 命令的名称
    protected $signature = 'shop-phone-member:install';
    // 命令的解释
    protected $description = '这个是组件安装命令';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->call('migrate');
        $this->call('vendor:publish', [
          "--provider"=>"Bruce\LaravelShopPhoneMember\Providers\MemberServiceProvider"
        ]);
    }
}
