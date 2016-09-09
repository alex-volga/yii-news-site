<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%notifications_on_event_roles}}".
 *
 * @property integer $noe_id
 * @property string $role_name
 *
 * @property NotificationsOnEvent $noe
 */
class NotificationsOnEventRoles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notifications_on_event_roles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['noe_id', 'role_name'], 'required'],
            [['noe_id'], 'integer'],
            [['role_name'], 'string', 'max' => 255],
            [['noe_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationsOnEvent::className(), 'targetAttribute' => ['noe_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'noe_id' => 'Noe ID',
            'role_name' => 'Role Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoe()
    {
        return $this->hasOne(NotificationsOnEvent::className(), ['id' => 'noe_id']);
    }
}
