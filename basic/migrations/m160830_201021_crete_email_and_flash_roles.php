<?php
use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use yii\db\Migration;

class m160830_201021_crete_email_and_flash_roles extends Migration
{
    public function safeUp()
    {
        Route::refreshRoutes();
        
        Role::create('EmailNotify');
        Role::create('SiteNotify');
    }

    public function safeDown()
    {
        Role::deleteIfExists(['name'=>'EmailNotify']);
        Role::deleteIfExists(['name'=>'SiteNotify']);
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
