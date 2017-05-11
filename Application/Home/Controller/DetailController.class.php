<?php

namespace Home\Controller;

use Think\Controller;

class DetailController extends CommonController
{

    public function index()
    {
        $id = intval($_GET['id']);
        if (!$id || $id < 0) {
            return $this->error("找不到资讯");
        }
        $news = D("News")->find($id);
        if (!$news || $news['status'] != 1) {
            return $this->error("找不到资讯");
        }
        //更新阅读量
        $count = intval($news['count']) + 1;
        D('News')->updateCount($id, $count);
        //文章内容
        $content = D("NewsContent")->find($id);
        $news['content'] = htmlspecialchars_decode($content['content']);
        //广告位内容
        $advNews = D("PositionContent")->select(array('status' => 1, 'position_id' => 5), 1);
        $rankNews = $this->getRank();

        $this->assign('result', array(
            'rankNews' => $rankNews,
            'advNews' => $advNews,
            'catId' => $news['catid'],
            'news' => $news,
        ));
        $this->display("Detail/index");
    }

    /**
     * 文章预览页面
     */
    public function view()
    {
        if (!getLoginUsername()) {
            $this->error("您没有权限访问该页面");
        }
        $this->index();
    }
}