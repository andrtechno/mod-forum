<?php

use panix\engine\Html;
use panix\engine\bootstrap\ActiveForm;
?>
<?php
$form = ActiveForm::begin();
?>
<div class="card">
    <div class="card-header">
        <h5><?= $this->context->pageName ?></h5>
    </div>
    <div class="card-body">
        <?= $form->field($model, 'pagenum') ?>
        <?= $form->field($model, 'edit_post_time') ?>
        <?= $form->field($model, 'enable_post_delete')->checkbox() ?>
        <?= $form->field($model, 'enable_guest_addtopic')->checkbox() ?>
        <?= $form->field($model, 'enable_guest_addpost')->checkbox() ?>
        

    </div>
    <div class="card-footer text-center">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
