<?php

namespace panix\mod\forum\models;

use Yii;
use panix\mod\user\models\User;
use panix\engine\db\ActiveRecord;

/**
 * Class Posts
 *
 * @property integer $id
 * @property integer $topic_id
 * @property integer $created_at
 * @property Topics $topic
 * @property string $slug
 * @property User $user
 * @property string $userName
 * @property string $userAvatar
 *
 * @package panix\mod\forum\models
 */
class Posts extends ActiveRecord
{

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';


    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%forum__posts}}';
    }

    public function getUserName()
    {
        return ($this->user) ? $this->user->username : Yii::t('app/default', 'GUEST');
    }

    /**
     * @param string $size
     * @return mixed|string
     */
    public function getUserAvatar($size = '100x100')
    {
        return ($this->user) ? $this->user->getAvatarUrl($size) : Yii::$app->user->getGuestAvatarUrl($size);
    }

    public function getUrl()
    {
        return ['/news/default/view', 'slug' => $this->slug];
    }


    public function afterSave($insert, $changedAttributes)
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findOne($this->user_id);
            $user->updateCounters(['forum_posts_count' => 1]);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['text', 'edit_reason'], 'string'],
            ['text', 'string', 'min' => 3],
            [['text', 'topic_id', 'user_id'], 'required'],
            //['date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            ['text', 'string', 'max' => 255],
            //['edit_user_id, user_id', 'numerical', 'integerOnly' => true],
            //  ['id, user_id, edit_user_id, edit_reason, edit_datetime, slug, text, full_text, date_update, date_create', 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserEdit()
    {
        return $this->hasOne(User::class, ['id' => 'edit_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topics::class, ['id' => 'topic_id']);
    }


    public function isEditPost()
    {
        if (Yii::$app->user->can('admin')) {
            return true;
        } else {
            if ($this->user_id == Yii::$app->user->id) {
                if (time() < $this->created_at + (int)Yii::$app->settings->get('forum', 'edit_post_time') * 60) {
                    return true;
                }
            }
        }
        return false;
    }

}
