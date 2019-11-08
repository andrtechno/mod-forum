<?php

namespace panix\mod\forum\models;

use Yii;
use panix\engine\db\ActiveRecord;
use panix\engine\behaviors\nestedsets\NestedSetsBehavior;
use panix\mod\forum\models\query\CategoriesQuery;
use yii\helpers\ArrayHelper;

/**
 * Class Categories
 *
 * @property integer $id
 * @property integer $lft
 * @property integer $level
 * @property integer $rgt
 * @property string $name
 * @property string $slug
 * @property string $full_path
 * @property string $image
 * @property boolean $switch
 * @property string $hint
 * @property integer $last_post_user_id
 * @property integer $last_post_id
 * @property integer $last_topic_id
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $count_posts
 * @property Topics $lastPost Relation of [[Topics]]
 *
 * @package panix\mod\forum\models
 */
class Categories extends ActiveRecord
{

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';

    public function rebuildFullPath()
    {
        // Create category full path.
        $ancestors = $this->ancestors()
            //->orderBy('depth')
            ->all();
        if ($ancestors) {
            // Remove root category from path
            unset($ancestors[0]);

            $parts = [];
            foreach ($ancestors as $ancestor)
                $parts[] = $ancestor->slug;

            $parts[] = $this->slug;
            $this->full_path = implode('/', array_filter($parts));
        }

        return $this;
    }
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
        return $this->hasOne(Posts::class, ['id' => 'last_post_id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            'tree' => [
                'class' => NestedSetsBehavior::class,
                // 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots'=>true
            ],
        ], parent::behaviors());
    }


}
