<?php


use yii\helpers\Html;
use panix\engine\bootstrap\ActiveForm;


?>


<div class="card">
    <div class="card-header">
        <h5><?= Html::encode($this->context->pageName) ?></h5>
    </div>
    <div class="card-body">
        <?php

        $form = ActiveForm::begin([
            'options' => [],

        ]);
        ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'access')->dropdownList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','name'),['multiple'=>'multiple']) ?>

    </div>
    <div class="card-footer text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/default', 'CREATE') : Yii::t('app/default', 'UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
