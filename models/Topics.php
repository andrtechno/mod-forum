<?php
namespace panix\mod\forum\models;

use Yii;;
use panix\mod\user\models\User;
class Topics extends \panix\engine\db\ActiveRecord {

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';

    public $text;


    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return '{{%forum__topics}}';
    }

    public function scopes() {
        return CMap::mergeArray(array(
                    'latast' => array(
                        'order' => 'date_create DESC'
                    ),
                        ), parent::scopes());
    }

    public function getUrl() {
        return ['/forum/topics/view', 'id' => $this->id];
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

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return [
            [['title', 'text'], 'string', 'min' => 3],
            [['title', 'text'], 'required'],
            ['is_close', 'boolean'],
           //['date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            ['title', 'string', 'max' => 140],
            [['id', 'user_id', 'title', 'date_update', 'date_create'], 'safe'],
        ];
    }
    public function getCategory() {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }
    
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    
    public function getPostsCount() {
        return $this->hasMany(Posts::class, ['topic_id' => 'id'])->count();
    }
    
    public function getPostsDesc() {
        return $this->hasMany(Posts::class, ['topic_id' => 'id'])->orderBy('id DESC');
    }
    public function getPosts() {
        return $this->hasMany(Posts::class, ['topic_id' => 'id'])->orderBy('id ASC');
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            //'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            //  'topicsCount' => array(self::STAT, 'ForumTopics', 'id'),
           // 'postsCount' => array(self::STAT, 'ForumPosts', 'topic_id'),
          //  'posts' => array(self::HAS_MANY, 'ForumPosts', 'topic_id', 'order' => '`posts`.`id` ASC'),
            //'postsDesc' => array(self::HAS_MANY, 'ForumPosts', 'topic_id', 'order' => '`postsDesc`.`id` DESC'),
           // 'category' => array(self::BELONGS_TO, 'ForumCategories', 'category_id'),
            'postLast' => array(self::BELONGS_TO, 'ForumPosts', 'last_post_id'),
                //'posts' => array(self::HAS_MANY, 'ForumPosts', 'topic_id', 'order'=>'`posts`.`date_create` DESC'),
                //'parent' => array(self::BELONGS_TO, 'ForumTopics', 'parent_id'),
                //'parents' => array(self::HAS_MANY, 'ForumTopics', 'parent_id'),
                //'parentsCount' => array(self::STAT, 'ForumTopics', 'parent_id'),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
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



}
