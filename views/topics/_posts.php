<?php
use panix\engine\CMS;
use panix\engine\Html;
?>
<div class="card panel-default forum-post" name="post-<?= ($index + 1) ?>" id="post-<?= ($index + 1) ?>">
    <div class="card-header clearfix">
        <div class="float-left">
            <?= ($model->user) ? $model->user->username : Yii::t('app', Yii::$app->user->guestName); ?>
        </div>
        <div class="float-right">
            <?php if (Yii::$app->user->can('admin')) { ?>
                <?= CMS::ip('195.78.247.104');//$model->ip_create ?>
            <?php } ?>
            <input type="checkbox" name="ads" class="" />
            <?= Html::a('#' . ($index + 1), '#post-' . ($index + 1)); ?>
            <?= Html::a(Html::tag('i', '', array('class' => 'icon-share')), '', array('class' => 'btn btn-link', 'style' => 'padding:0;')); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-4 text-center">

                <div style="margin:0 auto;">

                    <?php if ($model->user) { ?>
                        <?php //echo Html::img($model->user->getAvatarUrl("100x100"), $model->user->username, array('class' => 'img-thumbnail')) ?>

                    <?php } else { ?>

                        <?php //echo Html::img(Yii::$app->user->getAvatarUrl("100x100", true), Yii::$app->user->guestName, array('class' => 'img-thumbnail')) ?>


                    <?php } ?>

                </div>
                <?php if ($model->user) { ?>
                    <div>zzzzzzzzzzzzzzzzzzzzzz</div>
                    <div><?= Yii::t('forum/default', 'MESSAGES', array('num' => $model->user->forum_posts_count)) ?></div>    
                <?php } ?>

            </div>
            <div class="col-md-10 col-sm-9 col-xs-8">
                <div class="help-block"><?= Yii::t('forum/default', 'POST_SENDDATE'); ?> <?= CMS::date($model->created_at, true); ?></div>
                <div id="post-edit-ajax-<?= $model->id; ?>">
                    <?php echo $this->render('_posts_content', array('model' => $model)); ?>


                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">



        <a class="btn btn-xs btn-link" href="#">Скрыть</a>
        <a class="btn btn-xs btn-link" href="#">Жалоба</a>
        <?php if (!Yii::$app->user->isGuest) { ?>
            <a href="#" class="quote btn btn-xs btn-default">Цитата each</a>
            <a href="/forum/quote?post_id=<?= $model->id ?>" class="quote btn btn-xs btn-default">Ответить</a>
        <?php } ?>

        <?php if ($model->isEditPost()) { ?>

            <?php
           /* echo Html::ajaxLink('<i class="icon-edit"></i> Изменить', array('/forum/topics/editpost', 'id' => $model->id), array(
                'type' => 'GET',
                'data' => array(),
                'success' => 'js:function(data){
                    $("#post-edit-ajax-' . $model->id . '").html(data);
                    common.removeLoader();
                }',
                'beforeSend' => 'js:function(){
                    common.addLoader();
                }'
                    ), array('class' => 'btn btn-xs btn-link'));*/
            ?>


        <?php } ?>
        <?php if (Yii::$app->settings->get('forum', 'enable_post_delete')) { ?>
            <a class="btn btn-xs btn-link" href="#"><i class="icon-delete"></i> <?= Yii::t('app', 'DELETE') ?></a>
        <?php } ?>



    </div>
</div>
