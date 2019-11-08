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

    public function getUserName(){
        return ($this->user) ? $this->user->username : Yii::t('app', 'GUEST');
    }
    public function getUserAvatar(){
        return ($this->user) ? $this->user->getAvatarUrl("100x100") : Yii::$app->user->getGuestAvatarUrl("100x100");
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

    /**
     * @return array relational rules.
     */
    public function relations2()
    {
        return array(
            // 'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            //  'userEdit' => array(self::BELONGS_TO, 'User', 'edit_user_id'),
            'topic' => array(self::BELONGS_TO, 'ForumTopics', 'topic_id'),
        );
    }

    /**
     * @return array
     */
    public function behaviors2()
    {
        $a = array();
        $a['timeline'] = array(
            'class' => 'app.behaviors.TimelineBehavior',
            'attributes' => 'title',
        );
        if (Yii::$app->hasModule('comments')) {
            Yii::import('mod.comments.models.Comments');
            $a['comments'] = array(
                'class' => 'mod.comments.components.CommentBehavior',
                'owner_title' => 'title', // Attribute name to present comment owner in admin panel
            );
        }
        $a['timezone'] = array(
            'class' => 'app.behaviors.TimezoneBehavior',
            'attributes' => array('date_create', 'date_update'),
        );


        return CMap::mergeArray($a, parent::behaviors());
    }

    public static function getCSort()
    {
        $sort = new CSort;
        // $sort->defaultOrder = 't.ordern DESC';
        $sort->attributes = array(
            '*',
            'title' => array(
                'asc' => 'translate.title',
                'desc' => 'translate.title DESC',
            )
        );

        return $sort;
    }

    public function isEditPost()
    {
        if (Yii::$app->user->can('admin')) {
            return true;
        } else {
            if ($this->user_id == Yii::app()->user->id) {
                if (time() < strtotime($this->date_create) + (int)Yii::app()->settings->get('forum', 'edit_post_time') * 60) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

}
