<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%notifications_on_event}}".
 *
 * @property integer $id
 * @property string $model
 * @property string $event_name
 * @property string $notify_type
 * @property string $tpl_subject
 * @property string $tpl_body
 *
 * @property NotificationsOnEventRoles[] $notificationsOnEventRoles
 */
class NotificationsOnEvent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notifications_on_event}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'event_name', 'notify_type', 'tpl_body'], 'required'],
            [['model', 'tpl_body'], 'string'],
            [['event_name', 'tpl_subject'], 'string', 'max' => 255],
            [['notify_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model Name',
            'event_name' => 'Listener Event Name',
            'notify_type' => 'Notify Type',
            'tpl_subject' => 'Subject template',
            'tpl_body' => 'Message template',
            'roles' => 'Users roles'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(NotificationsOnEventRoles::className(), ['noe_id' => 'id']);
    }
    
    public function getRolesAsString()
    {
        return implode(', ', $this->rolesAsArray);
    }
    
    public function getRolesAsArray()
    {
        $list = $this->roles;
        if (!is_null($list)) {
            $res = [];
            foreach ($list as $role) {
                $res[] = $role->role_name;
            }
            return $res;
        }
        return [];
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function updateRoles($roles)
    {
        if ($roles) {
            \app\models\NotificationsOnEventRoles::deleteAll(['noe_id' => $this->id]);
            foreach ($roles as $role) {
                $rolesModel = new \app\models\NotificationsOnEventRoles();
                $rolesModel->noe_id = $this->id;
                $rolesModel->role_name = $role;
                $rolesModel->save();
            }
        }
    }
    
    public function renderSubject($model)
    {
        return \app\models\Notifications::renderTemplate($this->tpl_subject, $model);
    }
    
    public function renderBody($model)
    {
        return \app\models\Notifications::renderTemplate($this->tpl_body, $model);
    }
}
