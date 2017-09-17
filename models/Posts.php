<?php
namespace panix\mod\forum\models;

use Yii;
use panix\mod\user\models\User;
class Posts extends \panix\engine\db\ActiveRecord {

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';


    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return '{{%forum_posts}}';
    }

    public function scopes() {
        return CMap::mergeArray(array(
                    'latast' => array(
                        'order' => 'date_create DESC'
                    ),
                        ), parent::scopes());
    }

    public function getUrl() {
        return ['/news/default/view', 'seo_alias' => $this->seo_alias];
    }

    /**
     * Find news by url.
     * Scope.
     * @param string News url
     * @return News
     */
    public function withUrl($url) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'seo_alias=:url',
            'params' => array(':url' => $url)
        ));

        return $this;
    }

    public function afterSave($insert, $changedAttributes) {


        if (!Yii::$app->user->isGuest) {
            $user = User::findOne($this->user_id);
           $user->updateCounters(['forum_posts_count' => 1]);
        }


        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return [
            [['text', 'edit_reason'], 'string'],
            ['text', 'string', 'min' => 3],
            [['text', 'topic_id', 'user_id'], 'required'],
            //['date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            ['text', 'string', 'max' => 255],
            //['edit_user_id, user_id', 'numerical', 'integerOnly' => true],
          //  ['id, user_id, edit_user_id, edit_reason, edit_datetime, seo_alias, text, full_text, date_update, date_create', 'safe'],
       ];
    }
    
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
     public function getUserEdit() {
        return $this->hasOne(User::className(), ['id' => 'edit_user_id']);
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
           // 'user' => array(self::BELONGS_TO, 'User', 'user_id'),
          //  'userEdit' => array(self::BELONGS_TO, 'User', 'edit_user_id'),
            'topic' => array(self::BELONGS_TO, 'ForumTopics', 'topic_id'),
        );
    }

    /**
     * @return array
     */
    public function behaviors2() {
        $a = array();
        $a['timeline'] = array(
            'class' => 'app.behaviors.TimelineBehavior',
            'attributes' => 'title',
        );
        if (Yii::app()->hasModule('comments')) {
            Yii::import('mod.comments.models.Comments');
            $a['comments'] = array(
                'class' => 'mod.comments.components.CommentBehavior',
                'model' => 'mod.shop.models.ShopProduct',
                'owner_title' => 'title', // Attribute name to present comment owner in admin panel
            );
        }
        $a['timezone'] = array(
            'class' => 'app.behaviors.TimezoneBehavior',
            'attributes' => array('date_create', 'date_update'),
        );


        return CMap::mergeArray($a, parent::behaviors());
    }

    public static function getCSort() {
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

    public function isEditPost() {
        if (Yii::$app->user->can('admin')) {
            return true;
        } else {
            if ($this->user_id == Yii::app()->user->id) {
            if (time() < strtotime($this->date_create) + (int) Yii::app()->settings->get('forum', 'edit_post_time') * 60) {
                return true;
            } else {
                return false;
            }
            }else{
                return false;
            }
        }
    }

}
