<?php
/**
 * 作    者：niushiyao
 * 日    期：2017-12-09
 * 功能说明：升级控制器。
 **/

namespace Admin\Controller;

class CacheController extends ComController
{

    //清除缓存
    public function clear()
    {
        $cache = \Think\Cache::getInstance();
        $cache->clear();
        $this->rmdirr(RUNTIME_PATH);
        $this->success('系统缓存清除成功！');
    }

    //递归删除缓存信息

    public function rmdirr($dirname)
    {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        $dir = dir($dirname);
        if ($dir) {
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
    }

}