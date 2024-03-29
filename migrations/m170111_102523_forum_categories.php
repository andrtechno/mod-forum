<?php
/**
 * Generation migrate by PIXELION CMS
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 * Class m170111_102523_forum_categories
 */

use panix\engine\db\Migration;
use panix\mod\forum\models\Categories;

class m170111_102523_forum_categories extends Migration
{
    public $settingsForm = 'panix\mod\forum\models\SettingsForm';

    public function up()
    {
		$user = Yii::$app->user->identityClass;
        $this->createTable(Categories::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'lft' => $this->integer(11)->null()->unsigned(),
            'rgt' => $this->integer(11)->null()->unsigned(),
            'level' => $this->smallInteger(5)->null()->unsigned(),
            'tree' => $this->integer(11)->null()->unsigned(),
            'name' => $this->string(255)->null(),
            'hint' => $this->text()->null(),
            'slug' => $this->string(255)->null(),
            'full_path' => $this->string(255)->null(),
            'image' => $this->string(255)->null(),
            'last_topic_id' => $this->integer(11)->null()->unsigned()->comment('Последния тема'),
            'last_post_id' => $this->integer(11)->null()->unsigned()->comment('Последний пост'),
            'last_post_user_id' => $this->integer(11)->null()->unsigned()->comment('Последний автор поста'),
            'count_topics' => $this->integer(11)->defaultValue(0)->comment('Количество тем в категории'),
            'count_posts' => $this->integer(11)->defaultValue(0)->comment('Количество посто в категории'),
            'switch' => $this->boolean()->defaultValue(1),
            'views' => $this->integer(11)->defaultValue(0)->unsigned(),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
            'access' => $this->string(255)->null(),
        ], $this->tableOptions);

        $this->createIndex('lft', Categories::tableName(), 'lft');
        $this->createIndex('rgt', Categories::tableName(), 'rgt');
        $this->createIndex('tree', Categories::tableName(), 'tree');
        $this->createIndex('level', Categories::tableName(), 'level');
        $this->createIndex('full_path', Categories::tableName(), 'full_path');
        $this->createIndex('views', Categories::tableName(), 'views');
        $this->createIndex('switch', Categories::tableName(), 'switch');

        $this->addColumn($user::tableName(), 'forum_posts_count', $this->integer(11)->defaultValue(0)->unsigned());
        $this->loadSettings();
        Yii::$app->cache->flush();
    }

    public function down()
    {
		$user = Yii::$app->user->identityClass;
        $this->dropTable(Categories::tableName());
        $this->dropColumn($user::tableName(), 'forum_posts_count');
        Yii::$app->cache->flush();
    }

}
