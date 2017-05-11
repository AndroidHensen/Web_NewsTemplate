<?php
/*
 * 对话框传递参数
 */
function show($status, $message, $data = array())
{
    $result = array(
        'status' => $status,
        'message' => $message,
        'data' => $data,
    );
    exit(json_encode($result));
}

/*
 * 获取密码的md5
 */
function getMd5Password($password)
{
    return md5($password . C('MD5_PRE'));
}

/*
 * 修改前台数据的类型
 */
function getMenuType($type)
{
    return $type == 1 ? "后台菜单" : "前端导航";
}

/*
 * 修改前台数据的状态
 */
function status($status)
{
    if ($status == 0) {
        $str = '关闭';
    } elseif ($status == 1) {
        $str = '正常';
    } elseif ($status == -1) {
        $str = '删除';
    }
    return $str;
}

/*
 * 获取菜单点击路径
 */
function getAdminMenuUrl($nav)
{
    $url = '/index.php?m=' . $nav['m'] . '&c=' . $nav['c'] . '&a=' . $nav['f'];
    return $url;
}

/*
 * 获取点击导航高亮条目
 */
function getActive($navc)
{
    $c = strtolower(CONTROLLER_NAME);
    if (strtolower($navc) == $c) {
        return 'class = "active"';
    }
    return '';
}

/*
 * 获取King的状态
 */
function showKing($status, $data)
{
    header('Content-type:application/json;charset=UTF-8');
    if ($status == 0) {
        exit(json_encode(array('error' => 0, 'url' => $data)));
    }
    exit(json_encode(array('error' => 1, 'message' => "上传失败")));
}

/*
 * 获取登陆用户的用户名
 */
function getLoginUsername()
{
    return $_SESSION['adminUser']['username'] ? $_SESSION['adminUser']['username'] : '';
}

/*
 * 获取栏目名
 */
function getCatName($navs, $id)
{
    foreach ($navs as $nav) {
        $navList[$nav['menu_id']] = $nav['name'];
    }
    return isset($navList[$id]) ? $navList[$id] : '';
}

/*
 * 获取来源
 */
function getCopyFromById($id)
{
    $copyfrom = C('COPY_FROM');
    return $copyfrom[$id] ? $copyfrom[$id] : '';
}

/*
 * 获取缩略图
 */
function getThumb($thumb)
{
    if ($thumb) {
        return '<img style="height: 50px;width: 50px;" src=' . $thumb . ' />';
    } else {
        return '无';
    }
}