<?php

use panix\engine\Html;
use panix\engine\bootstrap\ActiveForm;
?>
<?php
$form = ActiveForm::begin();
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= $this->context->pageName ?></h3>
    </div>
    <div class="panel-body">
        <?= $form->field($model, 'pagenum') ?>
        <?= $form->field($model, 'edit_post_time') ?>
        <?= $form->field($model, 'enable_post_delete')->checkbox() ?>
        <?= $form->field($model, 'enable_guest_addtopic')->checkbox() ?>
        <?= $form->field($model, 'enable_guest_addpost')->checkbox() ?>
        

    </div>
    <div class="panel-footer text-center">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
