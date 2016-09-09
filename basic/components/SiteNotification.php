<?php
    namespace app\components;
    
    class SiteNotification extends \yii\base\Object
    {
        public function init()
        {
            $notifyList = \app\models\Notifications::getOnSite(\Yii::$app->getUser()->id);
            if (!is_null($notifyList)) {
                $msg = [];
                foreach ($notifyList as $notify) {
                    $msg[] = $notify->message;
                    $notify->delete();
                }
                if (!empty($msg)) {
                    \Yii::$app->session->setFlash('success', implode('<br>', $msg));
                }
            }
            parent::init();
        }
    }