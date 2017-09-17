<?php

namespace panix\mod\forum;

class Module extends \panix\engine\WebModule {

    public $edit_mode = true;
    public $_addonsArray;
    public $icon = 'comments';

    public function init2() {


        // Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum.css");
        //Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum-data.css");
        //Yii::app()->clientScript->registerScriptFile($this->assetsUrl . "/forum.js");
    }

    public $routes = [
        'forum' => 'forum/default/index',
        'forum/quote/*' => 'forum/default/quote',

        'forum/topic/addreply' => 'forum/topics/addreply',
        
        'forum/topic/<id>' => 'forum/topics/view',
        'forum/topic/<id>/*' => 'forum/topics/view',
        'forum/addcat/<parent_id>' => 'forum/default/addCat',
                'forum/category/<id>' => 'forum/default/view',
        'forum/editpost/<id>' => 'forum/topics/editpost',
        'forum/category/<id>/addtopic' => 'forum/topics/add',
    ];

    public function getAdminMenu() {
        return [
            'modules' => [
                'items' => [
                    [
                        'label' => 'forum',
                        'url' => ['/admin/forum'],
                        'icon' => Html::icon($this->icon),
                    ],
                ],
            ],
        ];
    }

}
