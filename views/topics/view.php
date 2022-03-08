<?php
use panix\engine\Html;
use panix\engine\CMS;
use panix\engine\widgets\Pjax;

/**
 * @var \panix\mod\forum\models\Topics $model
 */


?>

<h1><?= $model->title ?></h1>
<small>Автор <?= $model->user->username ?>, <?= CMS::date($model->created_at, true) ?></small>
<div class="forum">
    <div class="form-group float-right">
        <div class="dropdown">
            <a class="btn btn-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
               aria-expanded="true">
                Опции модератора

            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <a class="dropdown-item" href="#"><i class="icon-rename"></i> Редактировать заголовок</a>
                <a class="dropdown-item" href="#">Открыть тему</a>
                <a class="dropdown-item" href="#"><i class="icon-move"></i> Переместить тему</a>
                <div class="dropdown-item" role="separator"></div>
                <a class="dropdown-item" href="#">Объединить тему</a>
                <a class="dropdown-item" href="#">Скрыть</a>
                <a class="dropdown-item" href="#">Посмотреть историю (опция администратора)</a>
                <a class="dropdown-item" href="#">Отписать всех от этой темы</a>
                <a class="dropdown-item" href="#"><i class="icon-delete"></i> <?= Yii::t('app/default', 'DELETE') ?></a>
            </div>
            <?php if ($model->is_close) { ?>
                <a href="#" class="btn btn-danger"><i class="icon-locked"></i> Закрыта (нажмите для ответа)</a>
            <?php } ?>
        </div>


    </div>
    <div class="clearfix"></div>
    <div class="card">
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
        <div class="card">
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
        <div class="">Share block</div>

        <?php
        $session = 0;
        // $session = Session::model()->with('user')->findAllByAttributes(array('current_url' => Yii::$app->request->url));
        ?>

        <h4><?= Yii::t('forum/default', ($this->context->id == 'topics') ? 'VIEW_MEMBERS_TOPIC' : 'VIEW_MEMBERS_CAT', ['num' => $session]); ?></h4>
        <?php
        $t = 0;
        $guests = 0;
        $bots = 0;
        $users = 0;
        $readNames = array();
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
        echo implode(', ', $readNames);
        ?>


    </div>
</div>



