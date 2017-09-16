<div id="post-edit-<?= $data->id; ?>"><?= Html::text($data->text); ?></div>

<?php if ($data->userEdit) { ?>
    <p class="edit"><?= Yii::t('forum/default', 'POST_USER_EDIT', array('{user}' => $data->userEdit->login)); ?>: <?= CMS::date($data->edit_datetime, true, true); ?><span class="reason"><?= $data->edit_reason ?></span></p>
<?php } ?>