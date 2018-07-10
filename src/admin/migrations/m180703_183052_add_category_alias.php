<?php

use yii\db\Migration;

/**
 * Class m180703_183052_add_category_alias
 */
class m180703_183052_add_category_alias extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('news_cat', 'slug', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('news_cat', 'slug');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180703_183052_add_category_alias cannot be reverted.\n";

        return false;
    }
    */
}
