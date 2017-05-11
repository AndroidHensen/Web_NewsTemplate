<?php

namespace Common\Model;

use Think\Model;

class BasicModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('admin');
    }

    /**
     * 保存设置
     * @param array $data
     * @return mixed
     */
    public function save($data = array())
    {
        if (!$data) {
            throw_exception("没有提交的数据");
        }
        $id = F('basic_web_config', $data);
        return $id;
    }

    /**
     * 查询设置
     * @return mixed
     */
    public function select()
    {
        return F('basic_web_config');
    }
}