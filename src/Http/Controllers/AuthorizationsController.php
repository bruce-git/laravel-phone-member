<?php
namespace Bruce\LaravelShopPhoneMember\Http\Controllers;

use Illuminate\Http\Request;
use Bruce\LaravelShopPhoneMember\Models\User;
use Illuminate\Support\Facades\Auth;
use Bruce\LaravelShopPhoneMember\Facades\Member;
class AuthorizationsController  extends Controller
{

    public function wechatStore(Request $request)
    {
        // 获取微信的用户信息
        $wechatUser = session('wechat.oauth_user.default');
        $user = User::where("weixin_openid", $wechatUser->id)->first();
        if (!$user) {
            // 不存在记录用户信息
            $user = User::create([
                "nick_name"      => $wechatUser->name,
                "weixin_openid" => $wechatUser->id,
                "image_head"    => $wechatUser->avatar
            ]);
        }
        Member::login($user);
        return 'true';
    }

}