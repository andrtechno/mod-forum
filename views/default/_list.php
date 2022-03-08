<div class="card bg-dark">
    <div class="card-header">
        <?= Html::a($data->title, $data->getUrl(), array('title' => $data->title)) ?>
        <span class="float-right date-time"><?= CMS::date($data->date_create,true,true) ?></span>

    </div>
    <div class="card-body">
        <?= Html::text($data->short_text); ?>
    </div>
    <div class="card-footer">
        <?php if ($data->user) { ?>
            <span class="author"><?= Html::a($data->user->login, array('/users/profile/view', 'user_id' => $data->user->id)) ?></span>
        <?php } else { ?>
            <span class="author"><?= Yii::t('app/default','CHECKUSER',0)?></span>
        <?php } ?>
        <?php if (isset($data->commentsCount)) { ?><span class="review"><?= $data->commentsCount; ?> Комментариев</span><?php } ?>
       
        <?= Html::a(Yii::t('app/default', 'MORE'), $data->getUrl(), array('class' => 'float-right btn-link btn-xs read-more', 'title' => Html::decode(Yii::t('app/default', 'MORE')))) ?>
    </div>
</div>



