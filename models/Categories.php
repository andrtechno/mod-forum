<?php
namespace panix\mod\forum\models;

use Yii;
use panix\engine\behaviors\nestedsets\NestedSetsBehavior;
use panix\mod\forum\models\query\CategoriesQuery;
use panix\mod\forum\models\Topics;
class Categories extends \panix\engine\db\ActiveRecord {

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';

    public function checkAddTopic(){
        if(Yii::$app->user->isGuest && Yii::$app->settings->get('forum', 'enable_guest_addtopic')){
            return true;
        }else{
            if(!Yii::$app->user->isGuest){
                  return true;
            }
            return false;
        }
    }


    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return '{{%forum__categories}}';
    }

    public function scopes22() {
        return CMap::mergeArray(array(
                    'latast' => array(
                        'order' => 'date_create DESC'
                    ),
                        ), parent::scopes());
    }

    public function getUrl() {
        return ['/forum/default/view', 'id' => $this->id];
    }

    /**
     * Find news by url.
     * Scope.
     * @param string News url
     * @return News
     */
    public function withUrl($url) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'slug=:url',
            'params' => array(':url' => $url)
        ));

        return $this;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
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

    public function getTopicsCount() {
        return $this->hasMany(Topics::class, ['category_id' => 'id'])->count();
    }
    public function getTopics() {
        return $this->hasMany(Topics::class, ['category_id' => 'id'])->orderBy('id DESC');
    }
    public function getTopicsList() {
        return $this->hasMany(Topics::class, ['category_id' => 'id'])->orderBy('fixed DESC, updated_at DESC');
    }


    public function getLastPost() {
        return $this->hasMany(Posts::class, ['id'=>'last_post_id']);
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(

            //'topicsCount' => array(self::STAT, 'ForumTopics', 'category_id'),
            //'topics' => array(self::HAS_MANY, 'ForumTopics', 'category_id', 'order'=>'`topics`.`id` DESC'),
         //   'topicsList' => array(self::HAS_MANY, 'ForumTopics', 'category_id', 'order'=>'`topicsList`.`fixed` DESC, `topicsList`.`date_update` DESC'),

            

            
            //'lastTopic' => array(self::BELONGS_TO, 'ForumTopics', 'id','order'=>'`lastTopic`.`id` DESC'),
            //'lastPost' => array(self::BELONGS_TO, 'ForumPosts', 'id','order'=>'`lastPost`.`id` DESC'),
            'lastTopic' => array(self::BELONGS_TO, 'ForumTopics', 'last_topic_id'),

         //   'lastPost' => array(self::BELONGS_TO, 'ForumPosts', 'last_post_id'),

            

        );
    }
    public static function find() {
        return new CategoriesQuery(get_called_class());
    }


    public function behaviors() {
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
