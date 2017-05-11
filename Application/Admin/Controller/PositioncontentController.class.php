<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Exception;

class PositioncontentController extends CommonController
{
    public function index()
    {
        $data = array();
        //推荐位栏
        $position = D("Position")->getPositions();
        //搜索栏
        if ($_GET['title']) {
            $data['title'] = $_GET['title'];
            $this->assign('title', $data['title']);
        }
        $data['position_id'] = $_GET['position_id'] ? intval($_GET['position_id']) : $position[0]['id'];
        //分页操作
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : C('PAGE_SIZE');
        $positionContentCount = D('PositionContent')->getPositionsContentCount($data);
        $res = new \Think\Page($positionContentCount, $pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes', $pageRes);
        $contents = D("PositionContent")->select($data, $page, $pageSize);
        //搜索回显
        $this->assign('positionId', $data['position_id']);
        $this->assign('contents', $contents);
        $this->assign('positions', $position);
        $this->display();

    }

    /**
     * 添加
     */
    public function add()
    {
        if ($_POST) {
            if (!isset($_POST['position_id']) || !$_POST['position_id']) {
                return show(0, "推荐位ID不能为空");
            }
            if (!isset($_POST['title']) || !$_POST['title']) {
                return show(0, "推荐位标题不能为空");
            }
            if (!$_POST['url'] && !$_POST['news_id']) {
                return show(0, "url和news_id不能同时为空");
            }
            if (!isset($_POST['thumb']) || !$_POST['thumb']) {
                if ($_POST['news_id']) {
                    $res = D("News")->find($_POST['news_id']);
                    if ($res && is_array($res)) {
                        $_POST['thumb'] = $res['thumb'];
                    }
                } else {
                    return show(0, "推荐位图片不能为空");
                }
            }
            //修改数据
            if ($_POST['id']) {
                return $this->save($_POST);
            }
            //添加数据
            try {
                $id = D("PositionContent")->insert($_POST);
                if ($id) {
                    return show(1, "新增成功");
                }
                return show(0, "新增失败");
            } catch (Exception $e) {
                return show(0, $e->getMessage());
            }
        } else {
            $positions = D("Position")->getPositions();
            $this->assign('positions', $positions);
            $this->display();
        }
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $id = $_GET['id'];
        $position = D("PositionContent")->find($id);
        $positions = D("Position")->getPositions();
        $this->assign('positions', $positions);
        $this->assign('vo', $position);
        $this->display();
    }

    /**
     * 保存
     *
     * @param $data
     */
    public function save($data)
    {
        $id = $data['id'];
        unset($data['id']);
        try {
            $res = D("PositionContent")->updateById($id, $data);
            if ($res === false) {
                return show(0, "更新失败");
            }
            return show(1, "更新成功");
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    /**
     * 设置状态
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
                $res = D("PositionContent")->updateStatusById($id, $status);
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

    /**
     * 排序
     */
    public function listorder()
    {
        $listorder = $_POST['listorder'];
        $jump_url = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if ($listorder) {
            try {
                foreach ($listorder as $menuId => $v) {
                    $id = D('PositionContent')->updatePositionContentListorderById($menuId, $v);
                    //记录错误信息
                    if ($id === false) {
                        $errors[] = $menuId;
                    }
                }
            } catch (Exception $e) {
                return show(0, $e->getMessage(), array('jump_url' => $jump_url));
            }
            //如果有错误
            if ($errors) {
                return show(0, "排序失败-" . implode(',', $errors), array('jump_url' => $jump_url));
            }
            return show(1, "排序成功", array('jump_url' => $jump_url));
        }
        return show(0, "排序数据失败", array('jump_url' => $jump_url));
    }
}