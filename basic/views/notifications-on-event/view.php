<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationsOnEvent */

$this->title = 'Notification On Event #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notifications On Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-on-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'model',
            'event_name',
            'notify_type',
            [
              'label' => 'Users roles',
              'value'=> $model->rolesAsString
            ],
            'tpl_subject:ntext',
            'tpl_body:ntext'
        ],
    ]) ?>

</div>
