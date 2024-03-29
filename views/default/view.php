<?php
use panix\engine\Html;
use panix\engine\CMS;
?>

<?php
$subCategories = $model->children()->all();
?>
<div class="forum">

    <h1><?= $model->name ?></h1>


    <?php if (count($subCategories) > 0) { ?>
        <div class="card bg-dark">
            <div class="card-header">

                <?= Yii::t('forum/default','SUB_CATEGORIES'); ?>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">


                        <?php
                        foreach ($subCategories as $data) {
                            echo $this->render('partials/_categories_list', array('data' => $data));
                        }
                        ?>

                    </table>
                </div>
            </div>
        </div>

    <?php } ?>



    <?php echo $this->render('partials/_addtopic', array('model' => $model)); ?>
    <div class="clearfix"></div>

    <div class="card bg-dark">
        <div class="card-header">
            <?= $model->name ?>
        </div>
        <div class="card-body">
            <?php if ($model->topics) { ?>
                <div class="table-responsive">
                    <table class="table table-striped">


                        <?php foreach ($model->topicsList as $data) { ?>
                            <tr>
                                <td class="text-center"  width="2%">
                                    <?php if ($data->is_close) { ?>
                                        <i class="icon-locked" title="Тема закрыта"></i>
                                    <?php } ?>
                                    <?php if ($data->user_id == Yii::$app->user->id) { ?>
                                        <i class="icon-envelope" style="font-size:24px;"></i>
                                    <?php } else { ?>
                                        <i class="icon-star" style="font-size:24px;color:#ccc" title="Вы оставили сообщение в этой теме"></i>
                                    <?php } ?>


                                </td>
                                <td>
                                    <?php if ($data->fixed) { ?>
                                        <span class="badge badge-success"><?= Yii::t('forum/default', 'FIXED'); ?></span>
                                    <?php } ?>
                                    <?php if ($data->user_id == Yii::$app->user->id) { ?>
                                        <?= Html::a('<b>' . $data->title . '</b>', $data->getUrl()) ?>
                                    <?php } else { ?>
                                        <?= Html::a($data->title, $data->getUrl()) ?>
                                    <?php } ?>
                                    <br/>
                                    <?php if ($data->user) { ?>
                                        Автор: <?= Html::a($data->user->username, $data->user->getProfileUrl()) ?>,
                                    <?php } else { ?>
                                        <?= Yii::t('app/default', Yii::$app->user->guestName); ?>,
                                    <?php } ?>
                                    <?= CMS::date($data->created_at,true) ?>

                                    <?php
                                    $per_page = (int) Yii::$app->settings->get('forum', 'pagenum');
                                    $per_page = 10;
                                    //узнаем общее количество страниц и заполняем массив со ссылками
                                    $num_pages = ceil($data->postsCount / $per_page);
                                    if ($data->postsCount >= $per_page) {
                                        ?>



                                        <ul class="pagination pagination-xs">
                                            <?php for ($i = 1; $i <= $num_pages; $i++) { ?>
                                                <?php if ($i <= 3) { ?>
                                                    <li>
                                                        <?= Html::a($i,['/forum/topics/view','id' => $data->id, 'page' => $i],['title'=>CMS::date($data->created_at,true).' '.$data->title.' Перейти к странице '.$i]); ?>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ($num_pages >= 3) { ?>
                                                <li>
                                                    <?= Html::a($num_pages.' &rarr;',['/forum/topics/view','id' => $data->id, 'page' => $num_pages],['title'=>CMS::date($data->created_at,true).' '.$data->title.' Перейти к странице '.$num_pages]); ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </td>
                                <td width="15%" class="text-right">
                                    <div><?= Yii::t('app/default', 'VIEWS', ['n'=>$data->views]); ?></div>
                                    <div><b><?= ($data->postsCount > 0) ? $data->postsCount - 1 : 0 ?></b> <?= Yii::t('forum/default', 'POSTS', ['n'=>($data->postsCount > 0) ? $data->postsCount - 1 : 0]); ?></div>
                                <td width="20%">

                                    <?php if ($data->postsCount > 0) { ?>
                                        <?php if ($data->postsDesc[0]->user) { ?>
                                            <?= Html::a($data->postsDesc[0]->user->username, $data->postsDesc[0]->user->getProfileUrl()) ?>
                                        <?php } else { ?>
                                            ГОСТЬ!
                                        <?php } ?>

                                        <br/>
                                        <?= CMS::date($data->postsDesc[0]->created_at,true); ?>
                                    <?php } else { ?>

                                        <div class="text-center"><?= Yii::t('forum/default', 'NO_MESSAGES'); ?></div>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            <?php } else { ?>
                <div class="alert alert-info">Нет тем. Такое может быть если тем действительно в данном форуме еще не создавалось, или если выставлены фильтры просмотра списка тем.</div>

            <?php } ?>
        </div>
    </div>
    <?php echo $this->render('partials/_addtopic', array('model' => $model)); ?>



    <div class="">


        <?php
        $session = 0;
      //  $session = Session::model()->findAllByAttributes(array('current_url' => Yii::$app->request->url));
        ?>

        <div><?= Yii::t('forum/default', ($this->context->id == 'topics') ? 'VIEW_MEMBERS_TOPIC' : 'VIEW_MEMBERS_CAT', ['num' => $session]); ?></div>
        <?php
        $t = 0;
        $guests = 0;
        $bots = 0;
        $users = 0;

       /* foreach ($session as $val) {

            if ($val->user_type == 2 || $val->user_type == 3) {
                $users++;
            } elseif ($val->user_type == 1) {
                $bots++;
            } else {
                $guests++;
            }
            $t++;
        }*/
        ?>
        <div><?= $users ?> пользователей, <?= $guests ?> гостей, N/A анонимных</div>

        admin 


    </div>
</div>