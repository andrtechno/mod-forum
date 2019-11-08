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
        'action' => ['/forum/topic/add-reply'],
        'options'=>['id'=>'form-addreply']
    ]);


    ?>
    <?= $form->field($postModel, 'topic_id')->hiddenInput(['value' => $model->id])->label(false); ?>
    <?= $form->field($postModel, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>

    <div class="form-group">

        <?php //echo $form->field($postModel, 'text')->textarea(); ?>

        <?php echo $form->field($postModel, 'text')->widget(\panix\mod\forum\components\TinyMce::class); ?>
    </div>
    <div class="form-group">

        <a id="add-post-reply" class=" btn btn-primary"
           href="javascript:void(0)"><?= Yii::t('app', 'SEND') ?></a>
        <?= Html::submitButton('Расширенная форма', ['class' => 'btn btn-default']); ?>

    </div>
    <?php ActiveForm::end(); ?>
<?php } ?>


<?php $this->registerJs('
    $(function () {
        $("#add-post-reply").on("click", function () {
            tinyMCE.triggerSave();
            var f = $("#form-addreply");
            var action = f.attr("action");
            var serializedForm = f.serialize();
            serializedForm +="&json=1";
            //tinyMCE.triggerSave();
            $.ajax({
                type: "POST",
                url: action,
                data: serializedForm,
                dataType:"json",
                success: function (data, textStatus, request) {
                    if (data.success) {
                        common.notify(data.message,"success");
                        $.pjax.reload("#pjax-posts-list", {timeout : false});
                    }else{
                        $.each(data.errors, function(i,error){
                            common.notify(error,"error");
                        });
                        common.notify(data.message,"error");
                    }
                },
                error: function (req, status, error) {
                   console.log(error);
                }
            });
            return false;
        });
    });
',\yii\web\View::POS_END);
