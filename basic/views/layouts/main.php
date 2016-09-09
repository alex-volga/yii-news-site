<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use webvimark\modules\UserManagement\components\GhostMenu;
use webvimark\modules\UserManagement\UserManagementModule;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
     echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Login', 'url' => ['/user-management/auth/login'], 'visible' => Yii::$app->user->isGuest],
            ['label' => !Yii::$app->user->isGuest ? Yii::$app->user->username : 'User', 'visible' => !Yii::$app->user->isGuest,
              'items' => array_merge(array_map(
                    function($item)
                    {
                        $item['encode'] = false;
                        $item['visible'] = webvimark\modules\UserManagement\models\User::hasRole('Admin');
                        return $item;
                    }, UserManagementModule::menuItems()
                ),
                [
                    ['label' => 'Events', 'url' => ['/events'], 'visible' => webvimark\modules\UserManagement\models\User::hasRole('Admin')],
                    ['label' => 'Notifications on events', 'url' => ['/notifications-on-event'], 'visible' => webvimark\modules\UserManagement\models\User::hasRole('Admin')],
                    ['label' => 'Send message', 'url' => ['/site/send-message'], 'visible' => webvimark\modules\UserManagement\models\User::hasRole('Admin')],
                    ['label' => 'Add news', 'url' => ['/news/create'], 'visible' => webvimark\modules\UserManagement\models\User::hasPermission('addNews')],
                    ['label' => 'Profile', 'url' => ['/user/profile']],
                ],
                ['<li class="divider"></li>'],
                [
                    ['label' => 'Logout', 'url' => ['/user-management/auth/logout']]
                ])
            ],
            ['label' => 'Register', 'url' => ['/user-management/auth/registration'], 'visible' => Yii::$app->user->isGuest]
        ],
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php if(Yii::$app->session->getFlash('success')): ?>
            <div id="w1-success" class="alert-success alert fade in" >
                <i class="icon fa fa-check"></i><?= Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
