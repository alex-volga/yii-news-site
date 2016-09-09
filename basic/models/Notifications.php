<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%notifications}}".
 *
 * @property integer $id
 * @property string $message
 * @property string $subject
 * @property integer $user_id
 * @property string $delivery_type
 * @property integer $created_at
 *
 * @property User $user
 */
class Notifications extends \yii\db\ActiveRecord
{
    const NOTIFY_EMAIL = 'EmailNotify';
    const NOTIFY_SITE = 'SiteNotify';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notifications}}';
    }
    
    public static function getAllList()
    {
        return [
            self::NOTIFY_EMAIL,
            self::NOTIFY_SITE
        ];
    }
    
    public static function getAllNames()
    {
        return [
            self::NOTIFY_EMAIL => 'Email notifications',
            self::NOTIFY_SITE => 'Site notifications'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'user_id', 'delivery_type'], 'required'],
            [['message'], 'string'],
            [['user_id', 'created_at'], 'integer'],
            [['delivery_type'], 'string', 'max' => 30],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \webvimark\modules\UserManagement\models\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'message' => 'Message',
            'user_id' => 'User ID',
            'delivery_type' => 'Delivery Type',
            'created_at' => 'Created At',
        ];
    }
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                 ],
             ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\webvimark\modules\UserManagement\models\User::className(), ['id' => 'user_id']);
    }
    
    public static function getOnSite($userId)
    {
        return self::find()->where(['delivery_type' => self::NOTIFY_SITE])->andWhere(['user_id' => $userId])->all();
    }
    
    public static function getEmail($limit)
    {
        return self::find(['delivery_type' => self::NOTIFY_EMAIL])->limit($limit)->all();
    }
    
    public static function getMessagesList($type)
    {
        return self::findAll(['delivery_type' => $type]);
    }
      
    public static function send($notifyView, $subject, $model = null)
    {
        $list = self::getAllList();
        foreach ($list as $type) {
            self::sendOn($type, $notifyView, $subject, $model);
        }
    }

    private static function sendOn($typeNotify, $notifyView, $subject, $model = null)
    {
      if (file_exists(__DIR__.'/../notifications/'.$typeNotify.'/'.$notifyView.'.php')) {
          $messageText = $view->render('@app/notifications/'.$typeNotify.'/'.$notifyView, ['model' => $model]);
          self::sendCustom(
            $typeNotify,
            $typeNotify,
            $subject,
            $messageText
          );
      }
    }
    
    public static function sendCustom($type, $roles, $subject, $body)
    {
        $users = self::getUsersFor($roles);
        if (!is_null($users)) {
            $insertData = [];
            foreach ($users as $user) {
                $notify = new Notifications();
                $notify->subject = $subject;
                $notify->message = $body;
                $notify->user_id = $user['id'];
                $notify->delivery_type = $type;
                $notify->created_at = time();
                if ($notify->validate()) {
                    $insertData[] = $notify->attributes;
                }
            }
            self::sendBatch($insertData);
        }
    }
    
    public static function sendToUsers($type, $subject, $body, $users)
    {
        if (!is_null($users)) {
            $insertData = [];
            foreach ($users as $user) {
                $notify = new Notifications();
                $notify->subject = self::renderTemplate($subject, $user);
                $notify->message = self::renderTemplate($body, $user);
                $notify->user_id = $user->id;
                $notify->delivery_type = $type;
                $notify->created_at = time();
                if ($notify->validate()) {
                    $insertData[] = $notify->attributes;
                }
            }
            self::sendBatch($insertData);
        }
    }
    
    public static function sendToUser($type, $subject, $body, $user)
    {
        if (!is_null($user)) {
            $notify = new Notifications();
            $notify->subject = self::renderTemplate($subject, $user);
            $notify->message = self::renderTemplate($body, $user);
            $notify->user_id = $user->id;
            $notify->delivery_type = $type;
            $notify->created_at = time();
            return $notify->save();
        }
    }
    
    private static function sendBatch(array $insertData)
    {
        if (count($insertData) > 0) {
            $notify = new Notifications();
            \Yii::$app->db->createCommand()->batchInsert(self::tableName(), $notify->attributes(), $insertData)->execute();
        }
    }
    
    public static function getUsersFor($typeNotify)
    {
        $connection = \Yii::$app->db;
        $connection->open();

        $command = $connection->createCommand(
            "SELECT user.id, user.email FROM auth_assignment INNER JOIN user ON auth_assignment.user_id = user.id WHERE auth_assignment.item_name IN ('".implode('\', \'', $typeNotify)."') GROUP BY user.id;"
        );

        $users = $command->queryAll();
        $connection->close();

        return $users;
    }
    
    public static function renderTemplate($tpl, $model, $prefix = '')
    {
        if (empty($tpl)) {
            return '';
        }
        if (!is_null($model)) {
            if (preg_match_all('#\{%([A-Za-z_:]+)%\}#', $tpl, $keys)) {
                $keys = $keys[1];
                foreach ($keys as $path) {
                    $modelPath = explode('::', $path);
                    $val = $model;
                    foreach ($modelPath as $attr) {
                        if (isset($val->$attr)) {
                            $val = $val->$attr;
                        } else {
                            break;
                        }
                    }
                    if (is_string($val) || is_numeric($val)) {
                        $tpl = str_replace('{%'.$path.'%}', $val, $tpl);
                    }
                }
            }
        }
        return $tpl;
    }
}
