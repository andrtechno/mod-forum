<?php
use panix\mod\forum\models\Posts;
use yii\widgets\ActiveForm;
use panix\engine\Html;
if (!Yii::$app->user->isGuest) {




    //if (Yii::$app->user->hasFlash('success')) {

   //     Yii::$app->tpl->alert('success', Yii::$app->user->getFlash('success'));
   // }
    $postModel = new Posts;

$form = ActiveForm::begin([
            'options' => [
                'class' => 'form-horizontal',
            ]
        ]);


    ?>
    <?= $form->field($postModel, 'topic_id')->hiddenInput(['value'=> $model->id])->label(false); ?>
    <?= $form->field($postModel, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>

    <div class="form-group">

        <?php echo $form->field($postModel, 'text')->textarea(); ?>


    </div>
    <div class="form-group">

        <a id="add-post-reply" class=" btn btn-primary btn-upper" href="javascript:void(0)"><?= Yii::t('app', 'SEND') ?></a>
        <?= Html::submitButton('Расширенная форма', array('class' => 'btn btn-default')); ?>

    </div>
<?php ActiveForm::end(); ?>
<?php } ?>


<?php $this->registerJs('
    $(function () {
        $("#add-post-reply").on("click", function () {
            tinyMCE.triggerSave();
            var f = $("#addreply");
            var action = f.attr("action");
            var serializedForm = f.serialize();
            serializedForm +="&json=1";
            //tinyMCE.triggerSave();
            $.ajax({
                type: "POST",
                url: action,
                data: serializedForm,
                dataType:"json",
                //async: false,
                success: function (data, textStatus, request) {
                    //$("#ajax-addreply").html(data);
                    common.notify(data.message,"success");
                    $.fn.yiiListView.update("topic-list");
                },
                error: function (req, status, error) {
                    $("#ajax-addreply").html(data);
                }
            });
            return false;
        });
    });
');
