<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
New user registered: <?=Html::a($user->email, Url::to(['@web/user-management/user/view', 'id' => $user->id], true));?>.