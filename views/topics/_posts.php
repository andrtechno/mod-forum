<?php
use panix\engine\CMS;
use panix\engine\Html;

/**
 * @var \panix\mod\forum\models\Posts $model
 */


?>
<div class="card bg-dark forum-post" name="post-<?= ($index + 1) ?>" id="post-<?= ($index + 1) ?>">
    <div class="card-header clearfix">
        <div class="float-left">

            <small class="help-block"><?= Yii::t('forum/default', 'POST_DATE'); ?> <?= CMS::date($model->created_at, true); ?></small>
        </div>
        <div class="float-right">
            <?php if (Yii::$app->user->can('admin')) { ?>
                <?= CMS::ip($model->ip_create); ?>
                <input type="checkbox" name="post[]" value="<?= $model->id; ?>" class="post-checkbox"/>
            <?php } ?>

            <?= Html::a('#' . ($index + 1), '#post-' . ($index + 1)); ?>


            <span class="dropdown">
                <?= Html::a(Html::tag('i', '', ['class' => 'icon-share']), '', [
                    'class' => 'btn btn-link dropdown-toggle',
                    'aria-expanded' => 'true',
                    'aria-haspopup' => 'true',
                    'data-toggle' => 'dropdown',
                    'id' => 'dropdown-share-' . $index,
                    'style' => 'padding:0;'
                ]); ?>
                <div class="dropdown-menu" aria-labelledby="dropdown-share-<?= $index; ?>">
                    <a class="dropdown-item" target="_blank"
                       href="https://www.facebook.com/sharer/sharer.php?p[url]=<?= \yii\helpers\Url::to($model->getTopicUrl($index + 1),true); ?>&p[title]=Example Title"><i class="icon-facebook"></i> Facebook</a>
                    <a class="dropdown-item" target="_blank"
                       href="http://twitter.com/share?text=<?= $model->text; ?>&url=<?= \yii\helpers\Url::to($model->getTopicUrl($index + 1),true); ?>&hashtags=uo"><i
                                class="icon-twitter"></i> Twitter</a>
                    <a class="dropdown-item  d-none" target="_blank" href="#"><i class="icon-instagram"></i> Instagram</a>
                </div>
            </span>


        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-4 text-center">

                <div style="margin:0 auto;">
                    <?= Html::img($model->userAvatar, ['class' => 'img-thumbnail', 'alt' => $model->userName]) ?>
                </div>

                <div><?= Html::a($model->userName,['/user/default/viewprofile','id'=>$model->user_id]); ?></div>
                <?php if ($model->user) { ?>

                    <div><?= Yii::t('forum/default', 'MESSAGES', ['num' => $model->user->forum_posts_count]) ?></div>
                <?php } ?>

            </div>
            <div class="col-md-10 col-sm-9 col-xs-8">

                <div id="post-edit-ajax-<?= $model->id; ?>">
                    <?php echo $this->render('_posts_content', ['model' => $model]); ?>


                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">


        <a class="btn btn-sm btn-link  d-none" href="#">Скрыть</a>
        <a class="btn btn-sm btn-link  d-none" href="#">Жалоба</a>
        <?php if (!Yii::$app->user->isGuest) { ?>
            <a href="#" class="quote post-action-quote btn btn-sm btn-primary">Цитировать</a>
            <?= Html::a('Ответить', ['/forum/default/quote', 'post_id' => $model->id], ['class' => ' d-none1 no-fade quote btn btn-sm btn-primary']); ?>

        <?php } ?>
        <?php
        echo \panix\engine\widgets\like\LikeWidget::widget([
            'model' => $model
        ]);
        ?>
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
            <a class="btn btn-xs btn-link" href="#"><i class="icon-delete"></i> <?= Yii::t('app/default', 'DELETE') ?></a>
        <?php } ?>


    </div>
</div>
