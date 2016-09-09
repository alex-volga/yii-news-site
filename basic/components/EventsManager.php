<?php
    namespace app\components;
    
    use Yii;
    use yii\base\Event;
    use yii\db\ActiveRecord;
    use yii\base\Controller;
    use yii\base\Component;
    
    class EventsManager extends Component
    {
        public $userModel = '\app\models\User';
        public $adminAuthController = '\app\controllers\UserController';
        public $authController = '\app\controllers\AuthController';
        
        private $_newUser;
        
        public function init()
        {
            Event::on($this->adminAuthController, Controller::EVENT_AFTER_ACTION, function ($event) {
                if ($event->sender->action->id == 'create' && $this->_newUser) {
                    $this->onUserCreate($this->_newUser);
                    $this->_newUser = null;
                }
            });
            
            Event::on($this->userModel, ActiveRecord::EVENT_AFTER_INSERT, function ($event) {
                $this->_newUser = $event->sender;
            });
            
            Event::on($this->userModel, ActiveRecord::EVENT_AFTER_UPDATE, function ($event) {
                if (isset($event->changedAttributes['password_hash']) && !empty($event->sender->email_confirmed)) {
                    $this->onPasswordChange($event);
                }
            });
            
            $events = \app\models\Events::find()->where(['is_enabled' => 1])->all();
            if (!is_null($events)) {
                foreach ($events as $event) {
                    Event::on($event->model, $event->event_on, function ($event) {
                        Yii::trace($event->data->trigger_event_name.' trigger');
                        if ($event->sender) {
                            $event->sender->trigger($event->data->trigger_event_name);
                        }
                    }, $event);
                }
            }
            
            $notifOnEvents = \app\models\NotificationsOnEvent::find()->all();
            if (!is_null($notifOnEvents)) {
                foreach ($notifOnEvents as $notif) {
                    Event::on($notif->model, $notif->event_name, function ($event) {
                        \app\models\Notifications::sendCustom(
                            $event->data->notify_type,
                            $event->data->rolesAsArray,
                            $event->data->renderSubject($event->sender),
                            $event->data->renderBody($event->sender)
                        );
                    }, $notif);
                }
            }
            
            // Event::on(ActiveRecord::className(), 'onNewsInit', function ($event) {
                // Yii::trace('onNewsInit '.$event->sender->className());
            // });
        }
        
        private function onPasswordChange($event)
        {
            $user = $event->sender;
            $from = \Yii::$app->params['adminEmail'];
            $message = \Yii::$app->mailer
                ->compose(['html' => 'changepassword'], ['user' => $user])
                ->setFrom($from)
                ->setTo($user->email)
                ->setSubject('Your password has been changed');

            return $message->send();
        }
        
        private function onUserCreate($user)
        {
            \webvimark\modules\UserManagement\models\User::assignRole($user->id, 'Reader');
            $user->status = \webvimark\modules\UserManagement\models\User::STATUS_INACTIVE;
            $user->generateConfirmationToken();
            $user->save(false);
            
            Yii::$app->mailer
                ->compose('registrationEmailConfirmation', ['user' => $user])
                ->setFrom(Yii::$app->getModule('user-management')->mailerOptions['from'])
                ->setTo($user->email)
                ->setSubject(\webvimark\modules\UserManagement\UserManagementModule::t('front', 'Confirm registration') . ' ' . Yii::$app->name)
                ->send();
        }
    }