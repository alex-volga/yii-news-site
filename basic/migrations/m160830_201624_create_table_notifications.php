<?php

use yii\db\Migration;

class m160830_201624_create_table_notifications extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notifications}}', [
          'id' => $this->primaryKey(),
          'message' => $this->text()->notNull(),
          'user_id' => $this->integer()->notNull(),
          'delivery_type' => $this->string('30')->notNull(),
          'created_at' => $this->integer()->notNull(),
          'INDEX `delivery_type` (`delivery_type`)',
          'CONSTRAINT `FK_NOTIF_USER_ID_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%notifications}}');
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
