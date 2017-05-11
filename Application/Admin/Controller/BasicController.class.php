<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Exception;

class BasicController extends Controller
{

    public function index()
    {
        $result = D("Basic")->select();
        $this->assign('vo', $result);
        $this->display();
    }

    public function add()
    {
        if (!$_POST['title']) {
            return show(0, "站点信息不能为空");
        }
        if (!$_POST['keywords']) {
            return show(0, "站点关键字不能为空");
        }
        if (!$_POST['title']) {
            return show(0, "站点描述不能为空");
        }
        try {
            D("Basic")->save($_POST);
            return show(1, "配置成功");
        } catch (Exception $e) {
            $e->getMessage();
        }
        $this->display();
    }

    public function cache(){
        $this->display();
    }
}