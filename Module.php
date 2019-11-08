<?php

namespace panix\mod\forum;

use Yii;
use panix\engine\WebModule;
use yii\base\BootstrapInterface;

class Module extends WebModule implements BootstrapInterface
{

    public $edit_mode = true;
    public $icon = 'comments';

    public function init2()
    {


        // Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum.css");
        //Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum-data.css");
        //Yii::app()->clientScript->registerScriptFile($this->assetsUrl . "/forum.js");
    }

    public function bootstrap($app)
    {
        $app->urlManager->addRules(
            [
                //'forum' => 'forum/default/index',
                //'forum/quote/*' => 'forum/default/quote',

                //topics
                'forum/topic/<id:\d+>/page/<page:\d+>' => 'forum/topics/view',
                'forum/topic/<id:\d+>' => 'forum/topics/view',
                'forum/topic/<action:[0-9a-zA-Z_\-]+>' => 'forum/topics/<action>',
                'forum/topic/<action:[0-9a-zA-Z_\-]+>/<id:\d+>' => 'forum/topics/<action>',
                //'forum/edit-post/<id:\d+>' => 'forum/topics/<action>',

                //'forum/<action:[0-9a-zA-Z_\-]+>/<parent_id:\d+>' => 'forum/default/<action>',
                'forum/category/<id:\d+>' => 'forum/default/view',



                //'forum/category/<id:\d+>/add-topic' => 'forum/topics/add',




            ],
            true
        );


    }

    public function getAdminMenu()
    {
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

    public function getName()
    {
        return Yii::t('forum/default', 'MODULE_NAME');
    }

    public function getInfo()
    {
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
