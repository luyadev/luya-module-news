<?php

use yii\db\Migration;

class m190624_112612_news_article_scheduler extends Migration
{
    public function safeUp()
    {
        $this->addColumn('news_article', 'is_online', $this->boolean()->notNull()->defaultValue(false));
        $this->execute(
            <<<SQL
UPDATE news_article
SET is_online = (timestamp_display_from < NOW() AND (
    NOT is_display_limit OR (
        is_display_limit AND timestamp_display_until > NOW()
    )
));
SQL
        );
        // TODO add scheduler entries?
        $this->dropColumn('news_article', 'timestamp_display_from');
        $this->dropColumn('news_article', 'timestamp_display_until');
        $this->dropColumn('news_article', 'is_display_limit');
    }

    public function safeDown()
    {
        $this->addColumn('news_article', 'timestamp_display_from', $this->integer(11)->defaultValue(null));
        $this->addColumn('news_article', 'timestamp_display_until', $this->integer(11)->defaultValue(null));
        $this->addColumn('news_article', 'is_display_limit', $this->boolean()->defaultValue(false));

        // TODO restore timestamp_display data from schedulers?
        $this->update('news_article', ['timestamp_display_from' => time()], ['timestamp_display_from' => null]);
        $this->update('news_article', ['is_display_limit' => true], ['is_online' => false]);

        $this->dropColumn('news_article', 'is_online');
    }
}
