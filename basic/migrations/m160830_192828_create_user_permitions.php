<?php

use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use webvimark\modules\UserManagement\models\User;
use yii\db\Migration;

class m160830_192828_create_user_permitions extends Migration
{
    public function safeUp()
    {
        Route::refreshRoutes();
        
        $group = new AuthItemGroup();
        $group->name = 'News management';
        $group->code = 'newsManagment';
        $group->save(false);
        
        Role::create('Moderator');
        Role::assignRoutesViaPermission('Moderator','addNews', [
            '/news/create',
        ], 'Add news', $group->code);
        Role::addChildren('Admin', 'Moderator');
        
        Role::create('Reader');
        Role::assignRoutesViaPermission('Reader','viewFullNews', [
            '/news/index',
            '/site/index',
            '/news/view',
        ], 'View news', $group->code);
        Role::assignRoutesViaPermission('Reader','changeOwnPassword', [
            'user-management/auth/change-own-password'
        ]);

        Role::addChildren('Moderator', 'Reader');

        
        Role::assignRoutesViaPermission('Admin','editNews', [
            '/news/update',
        ], 'Edit news', $group->code);
        Role::assignRoutesViaPermission('Admin','deleteNews', [
            '/news/delete',
        ], 'Delete news', $group->code);
        
        User::assignRole(1, 'Admin');
    }

    public function safeDown()
    {
        Permission::deleteAll(['name'=>[
            'viewFullNews',
            'addNews',
            'editNews',
            'deleteNews'
        ]]);
        
        Role::removeChildren('Moderator', 'Reader');
        Role::removeChildren('Admin', 'Moderator');
        Role::deleteIfExists(['name'=>'Reader']);
        Role::deleteIfExists(['name'=>'Moderator']);
        
        AuthItemGroup::deleteAll([
            'code'=>[
                'newsManagment',
            ],
        ]);
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
