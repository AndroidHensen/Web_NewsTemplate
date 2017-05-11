<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Exception;
class ContentController extends CommonController
{

    public function index()
    {
        $cond = array();
        //搜索模块
        if ($_GET['title']) {
            $cond['title'] = $_GET['title'];
        }
        if ($_GET['catid']) {
            $cond['catid'] = $_GET['catid'];
        }
        //搜索回显
        $this->assign('type', $cond);
        //获取推荐位
        $position = D("Position")->getPositions();
        $this->assign('positions', $position);
        //分页
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : C('PAGE_SIZE');
        $news = D("News")->getNews($cond, $page, $pageSize);
        $count = D("News")->getNewsCount($cond);
        $res = new \Think\Page($count, $pageSize);
        $pageRes = $res->show();
        $this->assign('news', $news);
        $this->assign('pageRes', $pageRes);
        $this->assign('webSiteMenu', D("Menu")->getBarMenus());
        return $this->display();
    }

    /**
     * 增加
     */
    public function add()
    {
        if ($_POST) {
            if (!isset($_POST['title']) || !$_POST['title']) {
                return show(0, "标题不存在");
            }
            if (!isset($_POST['small_title']) || !$_POST['small_title']) {
                return show(0, "短标题不存在");
            }
            if (!isset($_POST['catid']) || !$_POST['catid']) {
                return show(0, "文章栏目不存在");
            }
            if (!isset($_POST['keywords']) || !$_POST['keywords']) {
                return show(0, "关键字不存在");
            }
            if (!isset($_POST['content']) || !$_POST['content']) {
                return show(0, "content不存在");
            }
            //更新操作
            if (isset($_POST['news_id'])) {
                return $this->save($_POST);
            }
            $newsId = D("News")->insert($_POST);
            if ($newsId) {
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D("NewsContent")->insert($newsContentData);
                if ($cId) {
                    return show(1, "新增成功");
                } else {
                    return show(0, "主表插入成功，副表插入失败");
                }
            } else {
                return show(0, "新增失败");
            }
        } else {
            $webSiteMenu = D('Menu')->getBarMenus();
            $titleFontColor = C("TITLE_FONT_COLOR");
            $copyFrom = C("COPY_FROM");
            $this->assign('webSiteMenu', $webSiteMenu);
            $this->assign('titleFontColor', $titleFontColor);
            $this->assign('copyfrom', $copyFrom);
            $this->display();
        }
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $newsId = $_GET['id'];
        if (!$newsId) {
            //执行跳转
            $this->redirect('/admin.php?c=content');
        }
        $news = D("News")->find($newsId);
        if (!$news) {
            //执行跳转
            $this->redirect('/admin.php?c=content');
        }
        $newsContent = D('NewsContent')->find($newsId);
        if ($newsContent) {
            $news['content'] = $newsContent['content'];
        }
        $webSiteMenu = D("Menu")->getBarMenus();
        $this->assign('webSiteMenu', $webSiteMenu);
        $this->assign('titleFontColor', C('TITLE_FONT_COLOR'));
        $this->assign('copyfrom', C("COPY_FROM"));
        $this->assign('news', $news);
        $this->display();
    }

    /**
     * 更新操作
     */
    public function save($data)
    {
        $newsId = $data['news_id'];
        unset($data['news_id']);
        try {
            $id = D("News")->updateById($newsId, $data);
            $newsContentData['content'] = $data['content'];
            $condId = D("NewsContent")->updateNewsById($newsId, $newsContentData);
            if ($id === false || $condId === false) {
                return show(0, '更新失败');
            }
            return show(1, '更新成功');
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
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
                $res = D("News")->updateStatusById($id, $status);
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

    /*
     *  更新排序操作
     */
    public function listorder()
    {
        $listorder = $_POST['listorder'];
        $jump_url = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if ($listorder) {
            try {
                foreach ($listorder as $menuId => $v) {
                    $id = D('News')->updateNewsListorderById($menuId, $v);
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

    /*
     * 推送操作
     */
    public function push()
    {
        $jump_url = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        $newId = $_POST['push'];

        if (!$newId || !is_array($newId)) {
            return show(0, "请选择推荐文章");
        }
        if (!$positionId) {
            return show(0, "没有选择推荐位");
        }
        try {
            $news = D("News")->getNewsByNewsIdIn($newId);
            if (!$news) {
                return show(0, "没有相关内容");
            }
            foreach ($news as $new) {
                $data = array(
                    'create_time' => $new['create_time'],
                    'position_id' => $positionId,
                    'title' => $new['title'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'thumb' => $new['thumb'],
                );
                $res = D("PositionContent")->insert($data);
                if (!$res) {
                    return show(0, "推荐不成功");
                }
            }
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        return show(1, "推荐成功", array('jump_url' => $jump_url));
    }


}