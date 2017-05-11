/*
 * 添加按钮
 */
$("#button-add").click(function () {
    var url = SCOPE.add_url;
    window.location.href = url;
});

/*
 * 提交表单
 */
$("#singcms-button-submit").click(function () {
    //拿到表单对象
    var data = $("#singcms-form").serializeArray();
    var postdata = {};
    //将表单对象转换成数组
    $(data).each(function (i) {
        postdata[this.name] = this.value;
    });
    var url = SCOPE.save_url;
    var jump_url = SCOPE.jump_url;
    //Ajax
    $.post(url, postdata, function (result) {
        if (result.status == 0) {
            return dialog.error(result.message);
        } else if (result.status == 1) {
            return dialog.success(result.message, jump_url);
        }
    }, "json");
});

/*
 Menu:修改操作
 */
$(".singcms-table #singcms-edit").on('click', function () {
    var id = $(this).attr('attr-id');
    var url = SCOPE.edit_url + '&id=' + id;
    window.location.href = url;
});

/*
 Menu:删除操作
 */
$(".singcms-table #singcms-delete").on('click', function () {
    var id = $(this).attr('attr-id');
    var a = $(this).attr('attr-a');
    var message = $(this).attr('attr-message');
    var url = SCOPE.set_status_url;

    data = {};
    data['id'] = id;
    data['status'] = -1;

    layer.open({
        type: 0,
        title: "是否提交？",
        btn: ['yes', 'no'],
        icon: 3,
        closeBtn: 2,
        content: "是否确定" + message,
        scrollbar: true,
        yes: function () {
            todelete(url, data);
        },
    });

    function todelete(url, data) {
        $.post(url, data, function (result) {
            if (result.status == 1) {
                return dialog.success(result.message, '');
            } else if (result.status == 0) {
                return dialog.error(result.message);
            }
        }, "json");
    }

});

/*
 Menu:排序操作
 */
$("#button-listorder").click(function () {
    var data = $("#singcms-listorder").serializeArray();
    var postdata = {};
    $(data).each(function (i) {
        postdata[this.name] = this.value;
    });
    var url = SCOPE.listorder_url;
    $.post(url, postdata, function (result) {
        if (result.status == 0) {
            //失败
            return dialog.error(result.message, result['data']['jump_url']);
        } else if (result.status == 1) {
            //成功
            return dialog.success(result.message, result['data']['jump_url']);
        }
    }, "json");
});
/*
 修改状态操作
 */
$(".singcms-table #singcms-on-off").on('click', function () {
    var id = $(this).attr('attr-id');
    var status = $(this).attr('attr-status');
    var url = SCOPE.set_status_url;

    data = {};
    data['id'] = id;
    data['status'] = status;

    layer.open({
        type: 0,
        title: "是否提交？",
        btn: ['yes', 'no'],
        icon: 3,
        closeBtn: 2,
        content: "是否确定更改状态",
        scrollbar: true,
        yes: function () {
            todelete(url, data);
        },
    });
    function todelete(url, data) {
        $.post(url, data, function (result) {
            if (result.status == 1) {
                return dialog.success(result.message, '');
            } else if (result.status == 0) {
                return dialog.error(result.message);
            }
        }, "json");
    }

});

/*
 推送到推送位
 */
$("#button-push").click(function () {
    var id = $("#select-push").val();
    if (id == 0) {
        return dialog.error("请选择推送位");
    }
    push = {};
    postData = {};
    $("input[name='pushcheck']:checked").each(function (i) {
        push[i] = $(this).val();
    });
    postData['push'] = push;
    postData['position_id'] = id;
    var url = SCOPE.push_url;
    $.post(url, postData, function (result) {
        if (result.status == 1) {
            return dialog.success(result.message, result['data']['jump_url']);
        }
        if (result.status == 0) {
            return dialog.error(result.message);
        }
    }, "json");
});