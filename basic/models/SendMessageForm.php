<?php
namespace app\models;

use yii\base\Model;

class SendMessageForm extends Model
{
    const STATE_ALL = 0;
    const STATE_ROLES = 1;
    const STATE_USERS = 2;
    
    public static $stateList = [
        self::STATE_ALL => 'All',
        self::STATE_ROLES => 'By roles',
        self::STATE_USERS => 'To users'
    ];
    
    public $subject;
    public $message;
    // public $types;
    public $roles;
    public $users;
    public $state;
    
    public function rules()
    {
        return [
            [['subject', 'message', 'state'], 'required'],
            [['subject', 'message'], 'trim'],
            ['subject', 'string', 'max' => 255],
            [['roles', 'users'], 'safe']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'subject' => 'Subject',
            'message' => 'Message',
            // 'types' => 'Types of notification',
            'roles' => 'Users roles',
            'users' => 'Users',
            'state' => 'Send to',
            'roles' => 'Roles'
        ];
    }
    
    public function send()
    {
        $users = [];
        $usersByType = [];
        switch ($this->state) {
            case self::STATE_ALL:
            case self::STATE_ROLES:
                $users = self::getUsersIdFor($this->state == self::STATE_ALL ? null : $this->roles, \app\models\Notifications::getAllList());
                break;
                
            case self::STATE_USERS:
                if (!is_null($this->users)) {
                    $listNotifyRoles = \app\models\Notifications::getAllList();
                    foreach ($this->users as $userId) {
                        $roles = \webvimark\modules\UserManagement\models\rbacDB\Role::getUserRoles($userId);
                        foreach ($roles as $role) {
                            if (in_array($role->name, $listNotifyRoles)) {
                                $users[] = ['id' => $userId, 'type' => $role->name];
                            }
                        }
                    }
                }
                break;
        }

        if (!empty($users)) {
            $listTypes = \app\models\Notifications::getAllList();
            foreach ($users as $user) {
                $userModel = \app\models\User::findOne($user['id']);
                if (!is_null($userModel)) {
                    Notifications::sendToUser(
                        $user['type'],
                        $this->subject,
                        $this->message,
                        $userModel
                    );
                }
            }
        }
    }
    
    private static function getUsersIdFor($roles, $subscribes)
    {
        $connection = \Yii::$app->db;
        $connection->open();

        $command = $connection->createCommand(
            "SELECT a2.user_id AS id, a2.item_name AS type FROM auth_assignment a1 LEFT JOIN auth_assignment a2 ON a1.user_id = a2.user_id  WHERE ".(!empty($roles) ? " a1.item_name IN ('".implode('\', \'', $roles)."') AND " : '')." a2.item_name IN ('".implode('\', \'', $subscribes)."') GROUP BY a2.user_id, a2.item_name;"
        );

        $users = $command->queryAll();
        $connection->close();

        return $users;
    }
}
