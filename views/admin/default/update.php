<?php


use yii\helpers\Html;
use panix\engine\bootstrap\ActiveForm;


?>



<?php

$form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],

        ]);
?>

<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

<?= $form->field($model, 'hint') ?>


<div class="form-group text-center">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'CREATE') : Yii::t('app', 'UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

