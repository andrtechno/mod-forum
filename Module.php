<?php

namespace panix\mod\forum;
use Yii;
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
                        'icon' => $this->icon,
                    ],
                ],
            ],
        ];
    }
    public function getName(){
        return Yii::t('forum/default', 'MODULE_NAME');
    }
    public function getInfo() {
        return [
            'label' => Yii::t('forum/default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => $this->icon,
            'description' => Yii::t('forum/default', 'MODULE_DESC'),
            'url' => ['/admin/forum'],
        ];
    }
}
