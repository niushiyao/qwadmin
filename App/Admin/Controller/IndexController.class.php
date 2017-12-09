<?php
/**
 * 作    者：niushiyao
 * 日    期：2017-12-09
 * 功能说明：后台首页控制器。
 **/

namespace Admin\Controller;

class IndexController extends ComController
{
    /**
     * 后台首页
     * @author niushiyao
     * @date   2017-12-09
     */
    public function index()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $t = time() - 3600 * 24 * 60;

        //删除60天前的日志
        $log = M('log');
        $log->where("create_time < $t")->delete();

        $pagesize = 15;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $log->count();
        $list = $log->order('id desc')->limit($offset . ',' . $pagesize)->select();

        //分页
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);

        $this->assign('mysql', mysql_get_server_info());
        $this->assign('nav', array('', '', ''));//导航
        $this->display();
    }
}