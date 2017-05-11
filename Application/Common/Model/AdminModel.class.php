<?php

namespace Common\Model;

use Think\Model;

class AdminModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('admin');
    }

    /**
     * 获取用户信息
     *
     * @param $username 用户名
     * @return mixed
     */
    public function getAdminByUsername($username)
    {
        $user = $this->_db->where('username="' . $username . '"')->find();
        return $user;
    }

    /**
     * 获取用户信息
     *
     * @param int $adminId 用户id
     * @return mixed
     */
    public function getAdminByAdminId($adminId = 0)
    {
        $res = $this->_db->where('admin_id=' . $adminId)->find();
        return $res;
    }

    /**
     * 更新用户登录时间
     *
     * @param $id 用户id
     * @param $data 最新时间
     * @return bool
     */
    public function updateByAdminId($id, $data)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        if (!$data || !is_array($data)) {
            throw_exception('更新的数据不合法');
        }
        return $this->_db->where('admin_id=' . $id)->save($data);
    }

    /**
     * 添加用户
     *
     * @param array $data
     * @return int|mixed
     */
    public function insert($data = array())
    {
        if (!$data || !is_array($data)) {
            return 0;
        }
        return $this->_db->add($data);
    }

    /**
     * 获取所有用户
     *
     * @return mixed
     */
    public function getAdmins()
    {
        $data = array(
            'status' => array('neq', -1),
        );
        return $this->_db->where($data)->order('admin_id desc')->select();
    }

    /**
     * 更新的状态
     *
     * @param $id 用户id
     * @param $status 用户状态
     * @return bool
     */
    public function updateStatusById($id, $status)
    {
        if (!is_numeric($status)) {
            throw_exception("status不能为非数字");
        }
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('admin_id=' . $id)->save($data);
    }

    /**
     * 获取今日登陆数量
     */
    public function getLastLoginUsers()
    {
        $time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $data = array(
            'status' => 1,
            'lastlogintime' => array("gt", $time),
        );
        $res = $this->_db->where($data)->count();
        return $res['tp_count'];
    }
}