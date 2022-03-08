<?php

use yii\bootstrap4\ActiveForm;
use panix\engine\Html;
?>


<h2><?= Yii::t('forum/default', 'TITLE_ADD_TOPIC', array('name' => $category->name)) ?></h2>


<?php
$form = ActiveForm::begin([
            'options' => [
                'class' => 'form-horizontal',
            ]
        ]);
?>
<div class="row">
    <div class="col-md-9">
        <div class="form-group">
            <?php echo $form->field($model, 'title'); ?>

        </div>
        <div class="form-group">

            <?php echo $form->field($model, 'text')->textarea(); ?>

        </div>
        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('forum/default', 'ADD_TOPIC'), ['class' => 'btn btn-primary']); ?>
        </div>

    </div>
    <div class="col-md-3">
        <div class="card bg-dark">
            <div class="card-header">Опции модератора</div>
            <div class="card-body">
                <div class="form-inline">
                    <label for="exampleInputFile">Лейбел</label>
                    <select class="form-control">
                        <option>test1</option>
                        <option>test1</option>
                    </select>
                    <p class="help-block">Example block-level help text here.</p>
                </div>
                <div class="">
                    <?php //echo $form->field($model, 'is_close')->checkbox(); ?>



                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>