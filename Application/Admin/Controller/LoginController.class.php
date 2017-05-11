<?php
namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller
{
    
    public function index()
    {
        //如果用户已经登陆过，跳转首页
        if (session('adminUser')) {
            $this->redirect('/index.php?m=admin&c=index&a=index');
        }
        $this->display();
    }

    /**
     * 用户登陆
     */
    public function check()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (!trim($username)) {
            return show(0, "用户名不能为空");
        }
        if (!trim($password)) {
            return show(0, "密码不能为空");
        }
        $user = D('Admin')->getAdminByUsername($username);
        if (!$user || $user['status'] !=1) {
            return show(0, "该用户不存在");
        }
        if ($user['password'] != getMd5Password($password)) {
            return show(0, "密码错误");
        }
        //更新登陆时间
        D("Admin")->updateByAdminId($user['admin_id'], array('lastlogintime' => time()));
        //保存登陆信息
        session('adminUser', $user);
        return show(1, "登陆成功");
    }

    /**
     * 退出登录
     */
    public function loginOut()
    {
        session('adminUser', null);
        $this->redirect('/index.php?m=admin&c=login&a=index');
    }

}