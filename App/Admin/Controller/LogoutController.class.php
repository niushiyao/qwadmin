<?php
/**
 * 作    者：niushiyao
 * 日    期：2017-12-09
 * 功能说明：后台退出登录控制器。
 **/

namespace Admin\Controller;

class LogoutController extends ComController
{
    public function index()
    {
        cookie('auth', null);
        session('uid',null);
        $url = U("login/index");
        header("Location: {$url}");
        exit(0);
    }
}