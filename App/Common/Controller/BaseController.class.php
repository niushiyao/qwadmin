<?php
/**
 * 作    者：niushiyao
 * 日    期：2017-12-09
 * 功能说明：管理后台模块公共控制器，用于储存公共数据。
 **/

namespace Common\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        //C(setting());
    }
}