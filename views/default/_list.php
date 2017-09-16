<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::link($data->title, $data->getUrl(), array('title' => $data->title)) ?>
        <span class="pull-right date-time"><?= CMS::date($data->date_create,true,true) ?></span>

    </div>
    <div class="panel-body">
        <?= Html::text($data->short_text); ?>
    </div>
    <div class="panel-footer">
        <?php if ($data->user) { ?>
            <span class="author"><?= Html::link($data->user->login, array('/users/profile/view', 'user_id' => $data->user->id)) ?></span>
        <?php } else { ?>
            <span class="author"><?= Yii::t('app','CHECKUSER',0)?></span>
        <?php } ?>
        <?php if (isset($data->commentsCount)) { ?><span class="review"><?= $data->commentsCount; ?> Комментариев</span><?php } ?>
       
        <?= Html::link(Yii::t('app', 'MORE'), $data->getUrl(), array('class' => 'pull-right btn-link btn-xs read-more', 'title' => Html::decode(Yii::t('app', 'MORE')))) ?>
    </div>
</div>



