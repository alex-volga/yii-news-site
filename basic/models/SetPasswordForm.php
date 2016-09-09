<?php
namespace app\models;

use webvimark\modules\UserManagement\UserManagementModule;
use yii\base\Model;
use Yii;

class SetPasswordForm extends Model
{
    /**
     * @var User
     */
    public $user;

    /*
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $repeat_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'repeat_password'], 'required'],
            [['password', 'repeat_password'], 'string', 'max'=>255],
            [['password', 'repeat_password'], 'trim'],
            ['repeat_password', 'compare', 'compareAttribute'=>'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'current_password' => UserManagementModule::t('back', 'Current password'),
            'password'         => UserManagementModule::t('front', 'Password'),
        ];
    }

    /**
     * @param bool $performValidation
     *
     * @return bool
     */
    public function changePassword($performValidation = true)
    {
        if ( $performValidation AND !$this->validate() )
        {
            return false;
        }

        $this->user->password = $this->password;
        // $this->user->removeConfirmationToken();
        return $this->user->save();
    }
}
