<?php
/**
 * 作    者：niushiyao
 * 日    期：2017-12-09
 * 功能说明：后台登录控制器。
 **/

namespace Admin\Controller;

use Admin\Controller\ComController;

class LoginController extends ComController
{
    public function index()
    {
        $flag = $this->check_login();
        if ($flag) {
            $this->error('您已经登录,正在跳转到主页', U("index/index"));
        }

        $this->display();
    }

    /**
     * 登录方法
     * @author niushiyao
     * @Date   2017-12-09
     */
    public function login()
    {
        $verify = isset($_POST['verify']) ? trim($_POST['verify']) : '';
        if (!$this->check_verify($verify, 'login'))
        {
            $this->error('验证码错误！', U("login/index"));
        }

        $username = isset($_POST['user']) ? trim($_POST['user']) : '';
        $password = isset($_POST['password']) ? password(trim($_POST['password'])) : '';
        $remember = isset($_POST['remember']) ? $_POST['remember'] : 0;
        if ($username == '') {
            $this->error('用户名不能为空！', U("login/index"));
        } elseif ($password == '') {
            $this->error('密码必须！', U("login/index"));
        }

        $model = M("Member");
        $user = $model->field('uid,user')->where(array('user' => $username, 'password' => $password))->find();

        if ($user) {
            $salt = C("COOKIE_SALT");
            $ip = get_client_ip();
            $ua = $_SERVER['HTTP_USER_AGENT'];
            session_start();
            session('uid',$user['uid']);
            //加密cookie信息
            $auth = password($user['uid'].$user['user'].$ip.$ua.$salt);
            if ($remember) {
                cookie('auth', $auth, 3600 * 24 * 365);//记住我
            } else {
                cookie('auth', $auth);
            }
            addlog('登录成功。');
            $url = U('index/index');
            header("Location: $url");
            exit(0);
        } else {
            addlog('登录失败。', $username);
            $this->error('登录失败，请重试！', U("login/index"));
        }
    }

    /**
     * 检查验证码
     * @param $code
     * @param string $id
     * @return bool
     */
    function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    /**
     * 生成验证码
     * @author niushiyao
     * @date   2017-12-09
     */
    public function verify()
    {
        $config = array(
            'fontSize' => 16, // 验证码字体大小
            'length' => 4, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'imageW' => 100,
            'imageH' => 30,
            'useCurve' => false,
        );
        $verify = new \Think\Verify($config);
        $verify->entry('login');
    }
}