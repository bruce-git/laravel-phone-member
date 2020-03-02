<?php
namespace Bruce\LaravelShopPhoneMember;

use Illuminate\Support\Facades\Auth;
/**
 * 提供给用户的工具类
 */
class Member
{
    public function guard()
    {
        return Auth::guard(config('phone.member.guard'));
    }

    //

    // 魔术方法
    // 零基础讲过 -> 当我们这个调用了不存在的方法 就会执行
    public function __call($method, $parameters)
    {
        // ...传递参数的方式
        return $this->guard()->$method(...$parameters);
    }
}
