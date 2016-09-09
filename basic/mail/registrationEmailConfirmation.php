<?php
/**
 * @var $this yii\web\View
 * @var $user webvimark\modules\UserManagement\models\User
 */
use yii\helpers\Html;

?>
<?php

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/set-password', 'token' => $user->confirmation_token]);
?>

Hello, you have been registered on <?= Yii::$app->urlManager->hostInfo ?>

<br/><br/>
Follow this link to set your password and activate account:

<?= Html::a('confirm registration', $confirmLink) ?>