<?php
namespace Common\Model;

use Think\Model;

class PositionModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('position');
    }

    public function getPositions($data, $page, $pageSize)
    {
        $data['status'] = array('neq', -1);
        $offset = ($page - 1) * $pageSize;
        $position = $this->_db->where($data)->limit($offset,$pageSize)->order('id')->select();
        return $position;
    }

    public function insert($data = array())
    {
        if (!is_array($data) || !$data) {
            return 0;
        }
        $data['create_time'] = time();
        return $this->_db->add($data);
    }

    public function updateStatusById($id, $status)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('id=' . $id)->save($data);
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

    public function getPositionsCount()
    {
        $data['status'] = array('neq', -1);
        $position = $this->_db->where($data)->count();
        return $position;
    }

}