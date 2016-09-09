<?php

use yii\db\Migration;

class m160904_205851_create_table_notifications_on_event extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notifications_on_event}}', [
          'id' => $this->primaryKey(),
          'model' => $this->text()->notNull(),
          'event_name' => $this->string('255')->notNull(),
          'notify_type' => $this->string('30')->notNull(),
          'tpl_subject' => $this->string('255')->notNull(),
          'tpl_body' => $this->text()->notNull()
        ], $tableOptions);
        
        $this->createTable('{{%notifications_on_event_roles}}', [
          'noe_id' => $this->integer()->notNull(),
          'role_name' => $this->string('255')->notNull(),
          'PRIMARY KEY (`noe_id`, `role_name`)',
          'CONSTRAINT `FK_NOE_ID` FOREIGN KEY (`noe_id`) REFERENCES `notifications_on_event` (`id`) ON UPDATE CASCADE ON DELETE CASCADE'
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%notifications_on_event_roles}}');
        $this->dropTable('{{%notifications_on_event}}');
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
