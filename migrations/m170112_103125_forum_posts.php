<?php

namespace panix\mod\forum\migrations;

/**
 * Generation migrate by PIXELION CMS
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 * Class m170112_103125_forum_posts
 */

use panix\engine\db\Migration;
use panix\mod\forum\models\Posts;

class m170112_103125_forum_posts extends Migration
{

    public function up()
    {
        $this->createTable(Posts::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer(11)->null()->unsigned(),
            'topic_id' => $this->integer(11)->null()->unsigned(),
            'score' => $this->integer(11)->defaultValue(0)->unsigned(),
            'rating' => $this->integer(11)->defaultValue(0)->unsigned(),
            'edit_user_id' => $this->integer(11)->null(),
            'text' => $this->text()->null(),
            'edit_reason' => $this->text()->null(),
            'user_agent' => $this->text()->null(),
            'ip_create' => $this->string(45),
            'edit_datetime' => $this->dateTime(),
            'switch' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable(Posts::tableName());
    }

}
