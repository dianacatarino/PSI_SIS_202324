<?php

use yii\db\Migration;

/**
 * Class m231114_155247_add_morada_to_empresa_table
 */
class m231114_155247_add_morada_to_empresa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231114_155247_add_morada_to_empresa_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231114_155247_add_morada_to_empresa_table cannot be reverted.\n";

        return false;
    }
    */
}
