<?php

use yii\db\Migration;

class m160829_183522_create_table_news extends Migration
{
    public function up()
    {
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }

      $this->createTable('{{%news}}', [
          'id' => $this->primaryKey(),
          'title' => $this->string('255')->notNull(),
          'preview_text' => $this->string('255')->notNull(),
          'full_text' => $this->text()->notNull(),
          'who_add_id' => $this->integer()->notNull(),
          'created_at' => $this->integer()->notNull(),
          'updated_at' => $this->integer(),
      ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%news}}');
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
