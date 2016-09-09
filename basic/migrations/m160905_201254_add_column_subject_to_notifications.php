<?php

use yii\db\Migration;

class m160905_201254_add_column_subject_to_notifications extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notifications}}', 'subject', 'VARCHAR(255)');
    }

    public function down()
    {
        $this->dropColumn('{{%notifications}}', 'subject');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
