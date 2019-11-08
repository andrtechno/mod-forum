<?php

namespace panix\mod\forum\models;

use Yii;
use panix\mod\user\models\User;
use panix\engine\db\ActiveRecord;

/**
 * Class Topics
 *
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $is_close
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $user_id
 * @property integer $category_id
 * @property Posts $posts
 * @property Posts $postsCount Posts count
 * @property Categories $category
 *
 * @package panix\mod\forum\models
 */
class Topics extends ActiveRecord
{

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';

    public $text;


    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%forum__topics}}';
    }

    public function getUrl()
    {
        return ['/forum/topics/view', 'id' => $this->id];
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'string', 'min' => 3],
            [['title', 'text'], 'required'],
            ['is_close', 'boolean'],
            //['created_at, updated_at', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            ['title', 'string', 'max' => 140],
            [['id', 'user_id', 'title', 'updated_at', 'created_at'], 'safe'],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPostsCount()
    {
        return $this->hasMany(Posts::class, ['topic_id' => 'id'])->count();
    }

    public function getPostsDesc()
    {
        return $this->hasMany(Posts::class, ['topic_id' => 'id'])->orderBy('id DESC');
    }

    public function getPosts()
    {
        return $this->hasMany(Posts::class, ['topic_id' => 'id'])->orderBy('id ASC');
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $a = array();
        if (Yii::$app->hasModule('comments')) {
            $a['comments'] = array(
                'class' => 'panix\mod\comments\components\CommentBehavior',
                //  'model' => 'mod.shop.models.ShopProduct',
                'owner_title' => 'title', // Attribute name to present comment owner in admin panel
            );
        }


        return $a;
    }

}
