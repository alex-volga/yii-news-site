<?php
    namespace app\commands;

    use yii\console\Controller;

    class EmailController extends Controller
    {
        
        public function actionIndex()
        {
            echo "Email send\n";
        }
        
        public function actionNotify()
        {
            $list = \app\models\Notifications::getEmail(100);
            if (!is_null($list)) {
                $from = \Yii::$app->params['adminEmail'];
                foreach ($list as $notify) {
                    $message = \Yii::$app->mailer
                    ->compose()
                    ->setHtmlBody($notify->message)
                    ->setFrom($from)
                    ->setTo($notify->user->email)
                    ->setSubject($notify->subject);

                    if ($message->send()) {
                        echo 'Send to: '.$notify->user->email."\n";
                        $notify->delete();
                    }
                }
            }
            return 0;
        }
        
        public static function notifyRegistration($user)
        {
            $list = \app\models\Notifications::getUsersFor('Admin');
            if (!is_null($list)) {
                $from = \Yii::$app->params['adminEmail'];
                foreach ($list as $admin) {
                    $message = \Yii::$app->mailer
                        ->compose(['html' => 'registration'], ['user' => $user])
                        ->setFrom($from)
                        ->setTo($admin['email'])
                        ->setSubject('New registration on site');

                    $message->send();
                }
            }
            return 0;
        }
    }