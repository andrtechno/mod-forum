<?php

use panix\engine\Html;
use panix\engine\CMS;
use panix\engine\widgets\Pjax;

/**
 * @var \panix\mod\forum\models\Topics $model
 */

$this->registerJs("
$(document).on('click','.post-action-delete',function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    var ids = Array();
    $('input.post-checkbox:checked').each(function () {
        //console.log($(this).val());
        ids.push($(this).val());
    });
    
    $.ajax({
        type:'POST',
        url:url,
        dataType:'json',
        data:{ids:ids},
        success:function(response){
            if (response.success) {
                common.notify(response.message,'success');
                $.pjax.reload('#pjax-posts-list', {timeout : false});
            }else{
                       // $.each(response.errors, function(i,error){
                       //     common.notify(error,'error');
                       // });
                common.notify(response.message,'error');
            }
        }
    });
    
    return false;
});
$(document).on('click','.post-action-quote',function(e){
tinyMCE.get('posts-text').setContent('[quote]dsaads[/quote]');
return false;
});
")
?>

<h1><?= $model->title ?></h1>
<small>Автор <?= $model->user->username ?>, <?= CMS::date($model->created_at, true) ?></small>
<div class="forum">
    <div class="form-group float-right">
        <?php if (Yii::$app->user->can('admin')) { ?>
            <div class="dropdown">
                <a class="btn btn-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="true">Опции модератора</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <a class="dropdown-item d-none" href="#"><i class="icon-rename"></i> Редактировать заголовок</a>
                    <a class="dropdown-item d-none" href="#">Открыть тему</a>
                    <a class="dropdown-item d-none" href="#"><i class="icon-move"></i> Переместить тему</a>
                    <div class="dropdown-item d-none" role="separator"></div>
                    <a class="dropdown-item d-none" href="#">Объединить тему</a>
                    <a class="dropdown-item d-none" href="#">Скрыть</a>
                    <?= Html::a((($model->is_close) ? '<i class="icon-locked"></i> Открыть тему' : '<i class="icon-locked"></i> Закрыть тему'), ['/forum/topics/close', 'id' => $model->id], ['class' => 'dropdown-item']); ?>
                    <a class="dropdown-item d-none" href="#">Посмотреть историю (опция администратора)</a>
                    <a class="dropdown-item d-none" href="#">Отписать всех от этой темы</a>
                    <a class="dropdown-item post-action-delete no-fade" href="<?= \yii\helpers\Url::to(['/forum/topics/post-delete','id'=>$model->id]); // ?>">
                        <i class="icon-delete"></i> <?= Yii::t('app/default', 'DELETE') ?>
                    </a>
                </div>
                <?php if ($model->is_close) { ?>
                    <a href="#" class="btn btn-danger"><i class="icon-locked"></i> Закрыта (нажмите для ответа)</a>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
    <div class="clearfix"></div>
    <div class="card bg-dark">
        <div class="card-header">
            <?php
            if ($model->postsCount >= 1) {
                echo Yii::t('forum/default', 'POST_MESSAGES_NUM', ['n' => $model->postsCount - 1]);
            } elseif ($model->postsCount <= 1) {
                echo Yii::t('forum/default', 'POST_MESSAGES_NO');
            } else {
                echo Yii::t('forum/default', 'POST_MESSAGES_ONE');
            }

            ?>

        </div>
        <div class="card-body  bg-info2">


            <?php
            Pjax::begin([
                'id' => 'pjax-posts-list',
            ]);
            echo \panix\engine\widgets\ListView::widget([
                'id' => 'posts-list',
                'dataProvider' => $providerPosts,
                'itemView' => '_posts',
            ]);
            Pjax::end();

            ?>


        </div>
    </div>
    <br/><br/>

    <?php if (!Yii::$app->user->isGuest) { ?>
        <div class="card bg-dark">
            <div class="card-header">
                Ответить
            </div>
            <div class="card-body">
                <?php if ($model->is_close) { ?>
                    <div class="alert alert-info"><span class=" text-danger">Обратите внимание, что эта тема закрыта, но вы можете отвечать в закрытые темы.</span>
                    </div>
                <?php } ?>

                <div id="ajax-addreply">
                    <?php
                    echo $this->render('_form_addreply', array('model' => $model, 'postModel' => array()));
                    ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">Чтобы оставить сообщение необходимо войти на сайт.</div>
    <?php } ?>


    <div class="">


        <?php
        $session = 0;
        $t = 0;
        $guests = 0;
        $bots = 0;
        $users = 0;
        $sessions = \panix\mod\user\models\Session::find()->all();
        $readsUsers = [];
        $readCount=0;
        echo date('Y-m-d H:i:s',1647712861);
        foreach ($sessions as $s) {
            if ($s->url == Yii::$app->request->getUrl()) {
                if ($s->user_type == 'User') {
                    $readsUsers[] = $s->user_name;
                }
                $readCount++;
            }
            if ($s->user_type == 'User') {
                $users++;
                $usersList[] = Html::a($s->user_name,['/user/default/viewprofile','id'=>$s->user_id]);

            }elseif($s->user_type == 'Guest'){
                $guests++;

            }
        }

        // $session = Session::model()->with('user')->findAllByAttributes(array('current_url' => Yii::$app->request->url));
        ?>

        <h4><?= Yii::t('forum/default', ($this->context->id == 'topics') ? 'VIEW_MEMBERS_TOPIC' : 'VIEW_MEMBERS_CAT', ['num' => $readCount]); ?></h4>
        <?php


        /*  foreach ($session as $val) {

              if ($val->user_type == 2 || $val->user_type == 3) {
                  $users++;

                  if ($val->user) {

                      $arrayAuthRoleItems = Yii::$app->authManager->getAuthItems(2, $val->user->id);
                      $roles = array_keys($arrayAuthRoleItems);


                      foreach ($roles as $role) {
                          if (in_array($role, array('Admin', 'Moderator'))) {
                              $login = Html::tag('b', array('class'=>'text-danger'), $val->user->username, true);
                          } else {
                              $login = $val->user->username;
                          }
                      }

                      $readNames[] = Html::a($login, $val->user->getProfileUrl());
                  }
              } elseif ($val->user_type == 1) {
                  $bots++;
                  $readNames[] = $val->user_login;
              } else {
                  $guests++;
              }

              $t++;
          }*/
        ?>
        <div><?= $users ?> пользователей, <?= $guests ?> гостей, N/A анонимных</div>
        <br/>
        <?php
        echo implode(', ', $usersList);
        ?>


    </div>
</div>



