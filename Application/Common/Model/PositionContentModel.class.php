<?php
namespace Common\Model;

use Think\Model;

class PositionContentModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('position_content');
    }

    public function insert($data)
    {
        if (!$data) {
            throw_exception("参数不合法");
        }
        $data['create_time'] = time();
        return $this->_db->add($data);
    }

    public function select($data, $page, $pageSize = 10)
    {
        $conditions = $data;
        if (isset($data['title']) && $data['title']) {
            $conditions['title'] = array('like', '%' . $data['title'] . '%');
        }
        $conditions['status'] = array('neq', -1);
        $offset = ($page - 1) * $pageSize;
        return $this->_db->where($conditions)
            ->order('listorder desc,id desc')
            ->limit($offset, $pageSize)
            ->select();
    }

    public function getPositionsContentCount($data)
    {
        $data['status'] = array('neq', -1);
        $position = $this->_db->where($data)->count();
        return $position;
    }

    public function find($id)
    {
        return $this->_db->where('id=' . $id)->find();
    }

    public function updateById($id, $data)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        if (!$data || !is_array($data)) {
            throw_exception("更新数据不合法");
        }
        return $this->_db->where('id=' . $id)->save($data);
    }


    public function updateStatusById($id, $status)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('id=' . $id)->save($data);
    }

    public function updatePositionContentListorderById($id, $listorder)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data = array(
            'listorder' => intval($listorder),
        );
        return $this->_db->where('id=' . $id)->save($data);
    }

}