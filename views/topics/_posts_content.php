<?php
$bbcode = new ChrisKonnertz\BBCode\BBCode();
?>
<div id="post-edit-<?= $model->id; ?>">
    <?= $bbcode->render($model->text); ?>


</div>

<?php if ($model->userEdit) { ?>
    <p class="edit"><?= Yii::t('forum/default', 'POST_USER_EDIT', array('user' => $model->userEdit->username)); ?>: <?= CMS::date($model->edit_datetime, true, true); ?><span class="reason"><?= $model->edit_reason ?></span></p>
<?php } ?>