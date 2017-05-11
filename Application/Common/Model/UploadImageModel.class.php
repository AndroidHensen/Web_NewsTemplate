<?php
namespace Common\Model;

use Think\Model;

/**
 * 上传图片类
 * @author  singwa
 */
class UploadImageModel extends Model
{
    private $_uploadObj = '';
    private $_uploadImageData = '';

    const UPLOAD = 'upload';

    public function __construct()
    {
        $this->_uploadObj = new  \Think\Upload();

        $this->_uploadObj->rootPath = './' . self::UPLOAD . '/';
        $this->_uploadObj->subName = date(Y) . '/' . date(m) . '/' . date(d);
    }

    /**
     * kindeditor.js上传图片
     * @return bool|string
     */
    public function upload()
    {
        $res = $this->_uploadObj->upload();

        if ($res) {
            return '/' . self::UPLOAD . '/' . $res['imgFile']['savepath'] . $res['imgFile']['savename'];
        } else {
            return false;
        }
    }

    /**
     * uploadify.js上传图片
     * @return bool|string
     */
    public function imageUpload()
    {
        $res = $this->_uploadObj->upload();

        if ($res) {
            return '/' . self::UPLOAD . '/' . $res['file']['savepath'] . $res['file']['savename'];
        } else {
            return false;
        }
    }
}
