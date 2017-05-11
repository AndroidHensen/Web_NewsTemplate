<?php

namespace Common\Model;

use Think\Model;

class NewsModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('news');
    }

    public function select($data = array(), $limit = 100)
    {
        $conditions = $data;
        $list = $this->_db->where($conditions)->order('news_id desc')->limit($limit)->select();
        return $list;
    }

    public function insert($data = array())
    {
        if (!is_array($data) || !$data) {
            return 0;
        }
        $data['create_time'] = time();
        $data['username'] = getLoginUsername();
        return $this->_db->add($data);
    }

    /**
     * 查询文章
     * @param $data
     * @param $page
     * @param int $pageSize
     * @return mixed
     */
    public function getNews($data, $page, $pageSize = 10)
    {
        $conditions = $data;
        if (isset($data['title']) && $data['title']) {
            $conditions['title'] = array('like', '%' . $data['title'] . '%');
        }
        if (isset($data['catid']) && $data['catid']) {
            $conditions['catid'] = intval($data['catid']);
        }
        $conditions['status'] = array('neq', -1);
        $offset = ($page - 1) * $pageSize;
        return $this->_db->where($conditions)
            ->order('listorder desc,news_id desc')
            ->limit($offset, $pageSize)
            ->select();
    }

    /**
     * 获取文章数量
     * @param array $data
     * @return mixed
     */
    public function getNewsCount($data = array())
    {
        $conditions = $data;
        if (isset($data['title']) && $data['title']) {
            $conditions['title'] = array('like', '%' . $data['title'] . '%');
        }
        if (isset($data['catid']) && $data['catid']) {
            $conditions['catid'] = intval($data['catid']);
        }
        return $this->_db->where($conditions)->count();
    }

    /**
     * 查询一条数据
     * @param array|mixed $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->_db->where('news_id=' . $id)->find();
    }

    /**
     * 更新
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        if (!$data || !is_array($data)) {
            throw_exception("更新数据不合法");
        }
        return $this->_db->where('news_id=' . $id)->save($data);
    }

    /**
     * 更新状态
     * @param $id
     * @param $status
     * @return bool
     */
    public function updateStatusById($id, $status)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('news_id=' . $id)->save($data);
    }

    /*
     * 通过id更新文章的排序
     */
    public function updateNewsListorderById($id, $listorder)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data = array(
            'listorder' => intval($listorder),
        );
        return $this->_db->where('news_id=' . $id)->save($data);
    }

    /**
     * 通过id数组获取对应的新闻数据
     */
    public function getNewsByNewsIdIn($newsIds)
    {
        if (!is_array($newsIds)) {
            throw_exception("参数不合法");
        }
        $data = array(
            'news_id' => array('in', implode(',', $newsIds)),
        );
        return $this->_db->where($data)->select();
    }

    /**
     * 获取排行的数据
     * @param array $data
     * @param int $limit
     * @return array
     */
    public function getRank($data = array(), $limit = 10)
    {
        $list = $this->_db->where($data)->order('count desc,news_id desc ')->limit($limit)->select();
        return $list;
    }

    /**
     * 更新阅读数
     * @param $id
     * @param $count
     * @return bool
     */
    public function updateCount($id, $count)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID 不合法");
        }
        if (!is_numeric($count)) {
            throw_exception("count不能为非数字");
        }
        $data['count'] = $count;
        return $this->_db->where('news_id=' . $id)->save($data);
    }

    /*
     * 获取最大阅读数
     */
    public function maxcount()
    {
        $data = array(
            'status' => 1,
        );
        return $this->_db->where($data)->order('count desc')->limit(1)->find();
    }

}