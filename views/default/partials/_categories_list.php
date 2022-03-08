<?php

use panix\engine\Html;
use panix\engine\CMS;

/**
 * @var \panix\mod\forum\models\Categories $data
 */

    $userAvatar = '';


?>
<tr>
    <td>
        <?= Html::a($data->name, $data->getUrl()) ?> <?= Html::a('<i class="icon-add"></i>', ['/forum/default/add-cat', 'parent_id' => $data->id], ['class' => 'btn btn-sm btn-success']) ?>
        <div class="help-block"><?= $data->hint ?></div>


    </td>
    <td width="15%" class="text-right">
        <div>
            <?= Yii::t('forum/default', 'TOPICS', ['n' => $data->count_topics]); ?>
        </div>
        <div>
            <?= Yii::t('forum/default', 'POSTS', ['n' => $data->count_posts]); ?>
        </div>
    </td>
    <td width="20%">


        <?php if ($data->topicsCount > 0) { ?>
            <div class="last_post_avatar">

                <?php
                if ($data->lastPost) {
                    $userAvatar = $data->lastPost->getUserAvatar('32x32');
                    echo Html::a(Html::img($userAvatar, [
                        'alt' => Yii::t('forum/default', 'LAST_POST_INFO', [
                            'title' => $data->topics[0]->title,
                            'username' => $data->lastPost->userName
                        ]),
                        'class' => 'img-thumbnail'
                    ]), $data->lastPost->user->getProfileUrl());
                } else {
                    echo '111111111111111';
                    echo Html::img($userAvatar, [
                        'alt' => Yii::t('forum/default', 'LAST_POST_INFO', [
                            'title' => $data->topics[0]->title,
                            'username' => $data->lastPost->userName
                        ]),
                        'class' => 'img-thumbnail'
                    ]);
                }

                ?>

            </div>
            <div class="last_post">
                <div><?= Html::a($data->topics[0]->title, $data->topics[0]->getUrl()); ?></div>
                <div>
                    От
                    <?php
                    if ($data->lastPost->user) {
                        echo Html::a($data->lastPost->userName,['/']);
                    }else{
                        echo $data->lastPost->userName;
                    }
                    ?>

                </div>
                <div title="<?= CMS::date($data->lastPost->created_at, true); ?>"><?= CMS::date($data->lastPost->created_at, false); ?></div>
            </div>
        <?php } else { ?>
            <span><?= Yii::t('forum/default', 'NO_MESSAGES') ?></span>
        <?php } ?>
    </td>
</tr>
