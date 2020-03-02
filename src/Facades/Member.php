<?php
namespace Bruce\LaravelShopPhoneMember\Facades;

use Illuminate\Support\Facades\Facade;
/**
 * 提供给用户的工具类
 */
class Member extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Bruce\LaravelShopPhoneMember\Member::class;
    }
}
