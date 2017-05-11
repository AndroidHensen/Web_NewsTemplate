<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends CommonController
{
    public function index($type = '')
    {
        //获取排行
        $rankNews = $this->getRank();
        //获取首页大图数据
        $topPicNews = D("PositionContent")->select(array('status' => 1, 'position_id' => 12), 1, 1);
        //首页3小图推荐
        $topSmailNews = D("PositionContent")->select(array('status' => 1, 'position_id' => 3), 1, 3);
        //新闻列表
        $listNews = D("News")->select(array('status' => 1, 'thumb' => array('neq', '')), 30);
        //广告位
        $advNews = D("PositionContent")->select(array('status' => 1, 'position_id' => 5), 1, 10);
        $this->assign('result', array(
            'topPicNews' => $topPicNews,
            'topSmailNews' => $topSmailNews,
            'listNews' => $listNews,
            'advNews' => $advNews,
            'rankNews' => $rankNews,
            'catId' => 0,
        ));
        /**
         * 生成页面静态化
         */
        if ($type == 'buildHtml') {
            $this->buildHtml('index', HTML_PATH, 'Index/index');
        } else {
            $this->display();
        }
    }

    /**
     * 生成页面静态化
     */
    public function build_html()
    {
        $this->index('buildHtml');
        return show(1, '首页缓存生成成功');
    }

    /*
     * 后台crontab命令，定时生成Html页面接口
     */
    public function crontab_build_html()
    {
        if (APP_CRONTAB != 1) {
            die("这个文件需要crontab来执行");
        }
        $result = D("Basic")->select();
        if (!$result['cacheindex']) {
            die('系统没有开启自动生成首页缓存的内容');
        }
        $this->index('buildHtml');
    }

    /*
     * 获取阅读量
     */
    public function getCount()
    {
        if (!$_POST) {
            return show(0, '没有任何内容');
        }
        //移除数组中重复的值
        $newsIds = array_unique($_POST);
        try {
            $list = D("News")->getNewsByNewsIdIn($newsIds);
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        if (!$list) {
            return show(0, 'notdataa');
        }

        $data = array();
        foreach ($list as $k => $v) {
            $data[$v['news_id']] = $v['count'];
        }
        return show(1, 'success', $data);
    }
}