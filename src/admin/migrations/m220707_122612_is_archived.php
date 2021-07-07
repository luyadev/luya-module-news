<?php

use yii\db\Migration;

class m220707_122612_is_archived extends Migration
{
    public function safeUp()
    {
        $this->addColumn('news_article', 'is_archived', $this->boolean()->defaultValue(false));
    }

    public function safeDown()
    {
        $this->dropColumn('news_article', 'is_archived', $this->boolean()->defaultValue(false));
    }
}
