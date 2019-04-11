<?php

namespace panix\mod\forum;
use Yii;
use panix\engine\WebModule;
use yii\base\BootstrapInterface;

class Module extends WebModule implements BootstrapInterface {

    public $edit_mode = true;
    public $_addonsArray;
    public $icon = 'comments';

    public function init2() {


        // Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum.css");
        //Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum-data.css");
        //Yii::app()->clientScript->registerScriptFile($this->assetsUrl . "/forum.js");
    }
    public function bootstrap($app)
    {
        $app->urlManager->addRules(
            [
                'forum' => 'forum/default/index',
                'forum/quote/*' => 'forum/default/quote',
                'forum/topic/addreply' => 'forum/topics/addreply',
                'forum/topic/<id:\d+>' => 'forum/topics/view',
                'forum/topic/<id:\d+>/*' => 'forum/topics/view',
                'forum/addcat/<parent_id:\d+>' => 'forum/default/addCat',
                'forum/category/<id:\d+>' => 'forum/default/view',
                'forum/editpost/<id:\d+>' => 'forum/topics/editpost',
                'forum/category/<id:\d+>/addtopic' => 'forum/topics/add',
            ],
            true
        );


    }

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
