var tree = $('#jsTree_CategoriesTree');
tree.bind('move_node.jstree', function (node, parent) {
    var xhr = $.ajax({
        type: 'GET',
        dataType: 'json',
        url: common.url('/admin/forum/default/move-node'),
        data: {
            'id': parent.node.id.replace('node_', ''),
            'ref': parent.parent.replace('node_', ''),
            'position': parent.position
        }
    });
});

tree.bind('rename_node.jstree', function (node, text) {
    if (text.old !== text.text) {
        var xhr = $.ajax({
            type: 'GET',
            url: common.url("/admin/forum/default/rename-node"),
            dataType: 'json',
            data: {
                "id": text.node.id.replace('node_', ''),
                text: text.text
            },
            success: function (data) {
                common.notify(data.message, 'success');
            },
            beforeSend: function () {
                common.addLoader();
            },
            complete: function () {
                common.removeLoader();
            }
        });
    }
});
//Need dev.replace(/-/g,'')
tree.bind('create_node.jstree', function (node, parent, position) {

    var xhr = $.ajax({
        type: 'GET',
        url: common.url("/admin/forum/default/create-node"),
        dataType: 'json',
        data: {
            text: parent.node.text,
            parent_id: parent.node.parent.replace(/node_|j1_/g, "")
        },
        success: function (data) {
            common.notify(data.message, 'success');
        },
        beforeSend: function () {
            common.addLoader();
        },
        complete: function () {
            common.removeLoader();
        }
    });
});

tree.bind("delete_node.jstree", function (node, parent) {
    var xhr = $.ajax({
        type: 'GET',
        dataType: 'json',
        url: common.url("/admin/forum/default/delete-node"),
        data: {
            "id": parent.node.id.replace('node_', '')
        }
    });
});

function categorySwitch(node) {
    var xhr = $.ajax({
        type: 'GET',
        url: common.url("/admin/forum/default/switch-node"),
        dataType: 'json',
        data: {
            id: node.id.replace('node_', ''),
        },
        success: function (data) {

            var icon = (data.switch) ? 'icon-eye' : 'icon-eye-close';
            common.notify(data.message, 'success');
            tree.jstree(true).set_icon(node, icon);
        },
        beforeSend: function () {
            common.addLoader();
        },
        complete: function () {
            common.removeLoader();
        }
    });
}




