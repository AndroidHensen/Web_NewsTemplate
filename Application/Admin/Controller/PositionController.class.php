<?php
namespace Admin\Controller;

use Think\Controller;

class PositionController extends CommonController
{
    public function index()
    {
        //分页操作
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : C('PAGE_SIZE');
        $positionCount = D('Position')->getPositionsCount();
        $res = new \Think\Page($positionCount, $pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes', $pageRes);
        $position = D('Position')->getPositions(array(), $page, $pageSize);
        $this->assign('positions', $position);
        $this->display();
    }

    /**
     * 增加
     */
    public function add()
    {
        //修改操作
        if ($_POST['id']) {
            $res = D("Position")->updateById($_POST['id'], $_POST);
            if ($res) {
                return show(1, "操作成功");
            } else {
                return show(0, "操作失败");
            }
        }
        //添加操作
        if ($_POST) {
            if (!isset($_POST['name']) || !$_POST['name']) {
                return show(0, "推荐位名称不存在");
            }
            if (!isset($_POST['description']) || !$_POST['description']) {
                return show(0, "推荐位描述不存在");
            }
            if (!isset($_POST['status']) || !$_POST['status']) {
                return show(0, "状态不存在");
            }
            $res = D("Position")->insert($_POST);
            if (!$res) {
                return show(0, "操作失败");
            }
            return show(1, "操作成功");
        }
        $this->display();
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $id = $_GET['id'];
        $res = D("Position")->find($id);
        $this->assign('vo', $res);
        $this->display();
    }

    /**
     * 修改状态
     */
    public function setStatus()
    {
        try {
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                if (!$id) {
                    return show(0, 'ID不存在');
                }
                $res = D("Position")->updateStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }

            }
            return show(0, "没有提交的内容");
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
    }
}