<?php

use yii\db\Migration;

class m220406_151512_link_and_author_fields extends Migration
{
    public function safeUp()
    {
        $this->addColumn('news_article', 'author', $this->string());
        $this->addColumn('news_article', 'link', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('news_article', 'author');
        $this->dropColumn('news_article', 'link');
    }
}
