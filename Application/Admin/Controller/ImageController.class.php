<?php
namespace Admin\Controller;

use Think\Controller;

class ImageController extends Controller
{
    /*
     * 图片上传
     */
    public function ajaxuploadimage()
    {
        $upload = D("UploadImage");
        $res = $upload->imageUpload();

        if ($res === false) {
            return show(0, "上传失败", '');
        } else {
            return show(1, "上传成功", $res);
        }
    }

    /*
     * KingEdit上传图片
     */
    public function kindupload()
    {
        $upload = D("UploadImage");
        $res = $upload->upload();
        if ($res === false) {
            return showKing(1,"上传失败");
        }
        return showKing(0,$res);
    }
}