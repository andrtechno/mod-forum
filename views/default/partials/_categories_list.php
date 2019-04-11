<?php

use panix\engine\Html;
use panix\engine\CMS;
?>
<tr>
    <td>
        <?= Html::a($data->name, $data->getUrl()) ?> <?= Html::a('<i class="icon-add"></i>', ['/forum/default/addCat', 'parent_id' => $data->id], array('class' => 'btn2 btn-xs2 btn-success')) ?>
        <div class="help-block"><?= $data->hint ?></div>


    </td>
    <td width="15%" class="text-right">
        <div><b><?= $data->count_topics ?></b> <?= CMS::GetFormatWord('forum/default', 'TOPICS', $data->count_topics); ?></div>
        <div><b><?= $data->count_posts ?></b> <?= CMS::GetFormatWord('forum/default', 'POSTS', $data->count_posts); ?></div>
    </td>
    <td width="20%">



        <?php if ($data->topicsCount > 0) { ?>
            <div class="last_post_avatar">
                <?php if ($data->topics[0]->user) { ?>
                    <?php echo Html::img($data->topics[0]->user->getAvatarUrl("25x25"), ['alt'=>$data->topics[0]->title . ' - последнее сообщение от ' . $data->topics[0]->user->getDisplayName(),'class' => 'img-thumbnail']) ?>
                <?php } else { ?>
                    <?php echo Html::img(Yii::$app->user->getAvatarUrl("25x25", true), ['alt'=>$data->topics[0]->title . ' - последнее сообщение от ' . Yii::$app->user->guestName,'class' => 'img-thumbnail']) ?>

                <?php } ?>
            </div>
            <div class="last_post">
                <?php
                echo Html::a($data->topics[0]->title, $data->topics[0]->getUrl());
                ?>
                <br>
                От <?php echo (isset($data->lastPost->user)) ? Html::a($data->lastPost->user->getDisplayName(), $data->lastPost->user->getProfileUrl()) : 'гость'; ?>
                <br/>
                <?php //echo CMS::date($data->lastPost->created_at,true); ?>

                <?php
               // print_r($data->lastPost);
                ?>
            </div>
        <?php } else { ?>
            <span><?= Yii::t('forum/default', 'NO_MESSAGES') ?></span>
        <?php } ?>
    </td>
</tr>
