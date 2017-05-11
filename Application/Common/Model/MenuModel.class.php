<?php
namespace Common\Model;

use Think\Model;

class MenuModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('menu');
    }

    /*
     * 插入数据
     */
    public function insert($data = array())
    {
        if (!$data || !is_array($data)) {
            return 0;
        }
        return $this->_db->add($data);
    }

    /*
     * 查询分页数据
     */
    public function getMenus($data, $page, $pageSize = 10)
    {
        $data['status'] = array('neq', -1);
        $offset = ($page - 1) * $pageSize;
        $menus = $this->_db->where($data)->order('listorder desc,menu_id desc')->limit($offset, $pageSize)->select();
        return $menus;
    }

    /*
     * 查询数据总数量
     */
    public function getMenusCount($data = array())
    {
        $data['status'] = array('neq', -1);
        return $this->_db->where($data)->count();
    }

    /*
     * 通过id查询数据
     */
    public function find($id)
    {
        if (!$id || !is_numeric($id)) {
            return array();
        }
        return $this->_db->where('menu_id=' . $id)->find();
    }

    /*
     * 通过id修改数据
     */
    public function updateMenuById($id, $data)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        if (!$data || !is_array($data)) {
            throw_exception("数据异常");
        }
        return $this->_db->where('menu_id=' . $id)->save($data);
    }

    /*
     * 通过id修改成删除状态
     */
    public function updateStatusById($id, $status)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        if (!$status || !is_numeric($status)) {
            throw_exception("状态不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('menu_id=' . $id)->save($data);
    }

    /*
     * 通过id更新菜单的排序
     */
    public function updateMenuListorderById($id, $listorder)
    {
        if (!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data = array(
            'listorder' => intval($listorder),
        );
        return $this->_db->where('menu_id=' . $id)->save($data);
    }

    /*
     * 获取后台菜单
     */
    public function getAdminMenu()
    {
        $data = array(
            'status' => array('neq', -1),
            'type' => 1,
        );
        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }

    /*
     * 获取前端导航和后台栏目
     */
    public function getBarMenus()
    {
        $data = array(
            'status' => array('neq', -1),
            'type' => 0,
        );
        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }

    /*
     * 获取前端导航
     */
    public function getBarMenu()
    {
        $data = array(
            'status' => 1,
            'type' => 0,
        );
        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }
}