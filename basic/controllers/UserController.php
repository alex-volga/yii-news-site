<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\SetPasswordForm;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['profile', 'set-password'],
                'rules' => [
                    [
                        'actions' => ['profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['set-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'profile' => ['get', 'post'],
                ],
            ],
        ];
    }


    public function actionProfile()
    {
        $data = [];
        $notifyTypes = \app\models\Notifications::getAllList();
        $notifyNames = \app\models\Notifications::getAllNames();
        $request = Yii::$app->request;
        $userId = Yii::$app->getUser()->id;
        $isChangeSettings = false;
        foreach ($notifyTypes as $type) {
            if (!empty($request->post())) {
                if (!empty($request->post($type))) {
                    User::assignRole($userId, $type);
                } else {
                    User::revokeRole($userId, $type);
                }
                $isChangeSettings = true;
            }
            $data[$type] = User::hasRole($type);
        }
        if ($isChangeSettings) {
            return $this->refresh();
        }
        return $this->render('profile', ['data' => $data, 'names' => $notifyNames]);
    }
    
    public function actionSetPassword($token)
    {
        if (!Yii::$app->user->isGuest ) {
            return $this->goHome();
        }
        
        $user = User::findInactiveByConfirmationToken($token);

        if ($user) {
            $model = new SetPasswordForm(['user'=>$user]);

            if (Yii::$app->request->isAjax AND $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post()) AND $model->changePassword()) {
                $model = new \webvimark\modules\UserManagement\models\forms\RegistrationForm();
                $user = $model->checkConfirmationToken($token);
                if ($user) {
                    return $this->render('SetPasswordSuccess');
                } else {
                    throw new NotFoundHttpException(\webvimark\modules\UserManagement\UserManagementModule::t('front', 'Token not found. It may be expired'));
                }
                
            }

            return $this->render('SetPassword', compact('model'));
        } else {
            throw new NotFoundHttpException(\webvimark\modules\UserManagement\UserManagementModule::t('front', 'Token not found. It may be expired'));
        }
    }
}