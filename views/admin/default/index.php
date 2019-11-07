<?php
use panix\mod\forum\models\Categories;
use panix\mod\forum\AdminAsset;

AdminAsset::register($this);

echo \panix\ext\jstree\JsTree::widget([
    'id' => 'CategoryTree',
    'name' => 'jstree',
    'data' => Categories::find()->dataTree(),
    // 'data' => CategoriesNode::fromArray(Categories::findOne(1)->children()->all(), ['switch' => true]),
    'core' => [
        'force_text' => true,
        'animation' => 0,
        'strings' => [
            'Loading ...' => Yii::t('app', 'LOADING')
        ],
        "themes" => ["stripes" => true, 'responsive' => true, "variant" => "large"],
        'check_callback' => true
    ],
    'plugins' => ['dnd', 'contextmenu', 'search', 'wholerow', 'state'],
    'contextmenu' => [
        'items' => new yii\web\JsExpression('function($node) {
                var tree = $("#jsTree_CategoryTree").jstree(true);
                return {
                    "Switch": {
                        "icon":"icon-eye",
                        "label": "' . Yii::t('app', 'Скрыть показать') . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                            categorySwitch($node);
                        }
                    }, 
                    "Add": {
                        "icon":"icon-add",
                        "label": "' . Yii::t('app', 'CREATE') . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                            console.log($node);
                            window.location = "/admin/forum/default/create?parent_id="+$node.id.replace("node_", "");
                        }
                    }, 
                    "Edit": {
                        "icon":"icon-edit",
                        "label": "' . Yii::t('app', 'UPDATE') . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                           window.location = "/admin/forum/default/update?id="+$node.id.replace("node_", "");
                        }
                    },  
                    "Rename": {
                        "icon":"icon-rename",
                        "label": "' . Yii::t('app', 'RENAME') . '",
                        "action": function (obj) {
                            console.log($node);
                            tree.edit($node);
                        }
                    },                         
                    "Remove": {
                        "icon":"icon-trashcan",
                        "label": "' . Yii::t('app', 'DELETE') . '",
                        "action": function (obj) { 
                            tree.delete_node($node);
                        }
                    }
                };
      }')
    ]
]);

/*$plugins = array();
//if (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.MoveNode'))) {
 //   Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', "Используйте 'drag-and-drop' для сортировки категорий."), false);
    $plugins[] = 'dnd';
//}
$plugins[] = 'search';
$plugins[] = 'contextmenu';
$plugins[] = 'wholerow';
$plugins[] = 'state';
$this->widget('ext.jstree.JsTree', array(
    'id' => 'ForumCategoriesTree',
    'data' => CategoriesNode::fromArray(Categories::model()->findAllByPk(1), array('switch' => false)),
    'options' => array(

        'core' => array(
            'force_text' => true,
            'animation' => 0,
            'strings' => array('Loading ...' => 'Please wait ...'),
            'check_callback' => true,
            "themes" => array("stripes" => true, 'responsive' => true),
            "check_callback" => 'js:function (operation, node, parent, position, more) {
                    console.log(operation);
                    if(operation === "copy_node" || operation === "move_node") {

                    } else if (operation === "delete_node"){
                    
                    } else if (operation === "rename_node") {

                    }
                      return true; // allow everything else
                    }
    
    
        '),
        'plugins' => $plugins,
        'contextmenu' => array(
            'items' => 'js:function($node) {
                var tree = $("#ForumCategoriesTree").jstree(true);
                return {
                    "Switch": {
                        "icon":"icon-eye",
                        "label": "' . Yii::t('app', 'Скрыть показать') . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.SwitchNode')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.SwitchNode')) ? Yii::t('app', 'Скрыть показать') : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                           // console.log($node);
                            categorySwitch($node);
                        }
                    }, 
                    "Add": {
                        "icon":"icon-add",
                        "label": "' . Yii::t('app', 'CREATE', 0) . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.CreateNode', 'Forum.Default.Create')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.CreateNode', 'Forum.Default.Create')) ? Yii::t('app', 'CREATE', 0) : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                        tree.create_node($node);
                          //  $node = tree.get_node($node);
                          //  window.location = "/admin/forum/default/create/parent_id/"+$node.id.replace("node_", "");
                        }
                    }, 
                    "Edit": {
                        "icon":"icon-edit",
                        "label": "' . Yii::t('app', 'UPDATE', 0) . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.Update')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.Update')) ? Yii::t('app', 'UPDATE', 0) : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                           window.location = "/admin/forum/default/update/id/"+$node.id.replace("node_", "");
                        }
                    },  
                    "Rename": {
                        "icon":"icon-rename",
                        "label": "' . Yii::t('app', 'RENAME') . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.RenameNode')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.RenameNode')) ? Yii::t('app', 'RENAME') : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            tree.edit($node);
                          
                        }
                    },                         
                    "Remove": {
                        "icon":"icon-trashcan",
                        "label": "' . Yii::t('app', 'DELETE') . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.Delete')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Forum.Default.*', 'Forum.Default.Delete')) ? Yii::t('app', 'DELETE') : Yii::t('error', '401')) . '",
                        "action": function (obj) { 
                            tree.delete_node($node);
                        }
                    }
                };
            }'
        )
    ),
));
*/
?>