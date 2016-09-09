<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\controllers\NotificationsOnEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications On Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-on-event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Notification On Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'model',
            'event_name',
            'notify_type',
            [
                'attribute'=>'roles',
                'content'=>function($data){
                    return $data->rolesAsString;
                }
            ],
            'tpl_subject:ntext',

            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
