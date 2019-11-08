<?php

namespace panix\mod\forum\models;

use Yii;
use panix\engine\db\ActiveRecord;
use panix\engine\behaviors\nestedsets\NestedSetsBehavior;
use panix\mod\forum\models\query\CategoriesQuery;

/**
 * Class Categories
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $hint
 * @property integer $last_post_user_id
 * @property integer $last_post_id
 * @property integer $last_topic_id
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $count_posts
 *
 * @package panix\mod\forum\models
 */
class Categories extends ActiveRecord
{

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';


    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%forum__categories}}';
    }

    public static function find()
    {
        return new CategoriesQuery(get_called_class());
    }

    public function getUrl()
    {
        return ['/forum/default/view', 'id' => $this->id];
    }

    public function checkAddTopic()
    {
        if (Yii::$app->user->isGuest && Yii::$app->settings->get('forum', 'enable_guest_addtopic')) {
            return true;
        } else {
            if (!Yii::$app->user->isGuest) {
                return true;
            }
            return false;
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['name', 'hint'], 'string'],
            ['name', 'string', 'min' => 3],
            ['name', 'required'],
            // ['date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            ['slug', 'string', 'max' => 255],
            ['name', 'string', 'max' => 140],
            [['id', 'name', 'slug', 'hint', 'updated_at', 'created_at'], 'safe'],
        ];
    }

    public function getTopicsCount()
    {
        return $this->hasMany(Topics::class, ['category_id' => 'id'])->count();
    }

    public function getTopics()
    {
        return $this->hasMany(Topics::class, ['category_id' => 'id'])->orderBy('id DESC');
    }

    public function getTopicsList()
    {
        return $this->hasMany(Topics::class, ['category_id' => 'id'])->orderBy('fixed DESC, updated_at DESC');
    }


    public function getLastPost()
    {
        return $this->hasMany(Posts::class, ['id' => 'last_post_id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::class,
                // 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
            ],
        ];
    }


}
