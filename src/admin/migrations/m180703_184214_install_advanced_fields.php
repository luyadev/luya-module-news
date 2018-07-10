<?php

use yii\db\Migration;

/**
 * Class m180703_184214_install_advanced_fields
 */
class m180703_184214_install_advanced_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('news_article', 'status', "ENUM('trash','published','pending','draft','auto-draft','inherit')");
        $this->addColumn('news_article', 'slug', $this->string());
        $this->addColumn('news_article', 'seo_title', $this->string());
        $this->addColumn('news_article', 'seo_keywords', $this->text());
        $this->addColumn('news_article', 'seo_description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('news_article', 'status');
        $this->dropColumn('news_article', 'slug');
        $this->dropColumn('news_article', 'seo_title');
        $this->dropColumn('news_article', 'seo_keywords');
        $this->dropColumn('news_article', 'seo_description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180703_184214_install_advanced_fields cannot be reverted.\n";

        return false;
    }
    */
}
