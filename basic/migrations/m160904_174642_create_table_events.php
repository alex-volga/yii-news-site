<?php

use yii\db\Migration;
use yii\db\ActiveRecord;

class m160904_174642_create_table_events extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%events}}', [
          'id' => $this->primaryKey(),
          'model' => $this->text()->notNull(),
          'event_on' => 'ENUM(
                "'.ActiveRecord::EVENT_AFTER_VALIDATE.'",
                "'.ActiveRecord::EVENT_BEFORE_VALIDATE.'",
                "'.ActiveRecord::EVENT_AFTER_INSERT.'",
                "'.ActiveRecord::EVENT_BEFORE_INSERT.'",
                "'.ActiveRecord::EVENT_AFTER_UPDATE.'",
                "'.ActiveRecord::EVENT_BEFORE_UPDATE.'",
                "'.ActiveRecord::EVENT_AFTER_DELETE.'",
                "'.ActiveRecord::EVENT_BEFORE_DELETE.'",
                "'.ActiveRecord::EVENT_AFTER_FIND.'",
                "'.ActiveRecord::EVENT_AFTER_REFRESH.'",
                "'.ActiveRecord::EVENT_INIT.'"
            )',
          'trigger_event_name' => $this->string('255')->notNull(),
          'is_enabled' => 'TINYINT(1) NOT NULL DEFAULT "0"'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%events}}');
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
