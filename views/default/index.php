<?php

use panix\engine\Html;
use panix\mod\user\models\User;
use panix\mod\forum\ForumAsset;

ForumAsset::register($this);
$total_posts = 0;

?>
<div class="forum">
    <h1><?= $this->context->pageName; ?></h1>

    <?php foreach ($categories as $category) {
        if ($category->isAccess()) {
            ?>

            <div class="card bg-dark mb-3">
                <div class="card-header">
<h5 class="m-0">
                    <?= $category->name ?>
</h5>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <?php
                            foreach ($category->children()->published()->all() as $data) {
                                $total_posts += $data->count_posts;
                                echo $this->render('partials/_categories_list', array('data' => $data));
                            }
                            ?>
                        </table>
                    </div>

                </div>
            </div>
            <?php

        }


    } ?>

    <div class="row">
        <div class="col-md-3 text-center">
        <span class="badge badge-secondary">
            <?= $total_posts ?>
        </span>
            <?= Yii::t('forum/default', 'Всего сообщений'); ?>
        </div>
        <div class="col-md-3 text-center">
        <span class="badge badge-secondary">
            <?= User::find()->count(); ?>
        </span>
            <?= Yii::t('forum/default', 'Пользователей'); ?>
        </div>
        <div class="col-md-3 text-center">
            <span class="badge badge-secondary"><?php //echo User::find()->lastRecord()->find()->login; ?></span> <?= Yii::t('forum/default', 'Новый участник'); ?>
        </div>
        <div class="col-md-3 text-center">
            <span class="badge badge-secondary">2</span>
            <?= Yii::t('forum/default', 'Рекорд посещаемости'); ?>
        </div>
    </div>

    <div class="">


        <?php
        $total = 0;
        $guests = 0;
        $bots = 0;
        $users = 0;
$usersList = [];
        $session = 0;
        //$session = Session::model()->findAllByAttributes(array('current_url' => Yii::app()->request->url));
        $sessions = \panix\mod\user\models\Session::find()->all();

        foreach ($sessions as $s){

            if($s->user_type == 'User'){
                $users++;
                $usersList[]=$s->user_name;
                $total++;
            }
        }
        ?>

        <div><?= Yii::t('forum/default', ($this->context->id == 'topics') ? 'VIEW_MEMBERS_TOPIC' : 'VIEW_MEMBERS_CAT', ['num' => $session]); ?></div>
        <?php


        /*foreach ($session as $val) {

            if ($val->user_type == 2 || $val->user_type == 3) {
                $users++;
            } elseif ($val->user_type == 1) {
                $bots++;
            } else {
                $guests++;
            }
            $total++;
        }*/
        ?>
        <div>Пользователей онлайн: <?= $total ?> (за последние 15 минут)</div>



        <?php echo implode(', ',$usersList); ?>

    </div>


    <div>

        <a class="btn btn-link" href="#">Администрация</a>
        <a class="btn btn-link" href="#">Самые активные сегодня</a>
        <a class="btn btn-link" href="#">Самые активные Самый</a>
        <a class="btn btn-link" href="#">Популярный Контент</a>

    </div>
</div>

