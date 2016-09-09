<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%events}}".
 *
 * @property integer $id
 * @property string $model
 * @property string $event_on
 * @property string $trigger_event_name
 * @property integer $is_enabled
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%events}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'trigger_event_name'], 'required'],
            [['model', 'event_on'], 'string'],
            [['is_enabled'], 'integer'],
            [['trigger_event_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'event_on' => 'Event On',
            'trigger_event_name' => 'Trigger Event Name',
            'is_enabled' => 'Is Enabled',
        ];
    }
}
