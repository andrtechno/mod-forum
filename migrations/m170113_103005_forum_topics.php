<?php


/**
 * Generation migrate by PIXELION CMS
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 * Class m170113_103005_forum_topics
 */

use panix\engine\db\Migration;
use panix\mod\forum\models\Topics;

class m170113_103005_forum_topics extends Migration
{

    public function up()
    {
        $this->createTable(Topics::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer(11)->null()->unsigned(),
            'category_id' => $this->integer(11)->null()->unsigned(),
            'title' => $this->string(255)->null(),
            'is_close' => $this->boolean()->defaultValue(0),
            'fixed' => $this->boolean()->defaultValue(0),
            'last_post_id' => $this->integer(11)->null(),
            'views' => $this->integer(11)->defaultValue(0)->unsigned(),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null()
        ], $this->tableOptions);
    }


    public function down()
    {
        $this->dropTable(Topics::tableName());
    }

}
