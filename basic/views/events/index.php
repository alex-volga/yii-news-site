<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\controllers\EventsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'model',
            'event_on',
            'trigger_event_name',
            [
                'attribute'=>'is_enabled',
                'content'=>function($data) {
                    return $data->is_enabled == 1 ? 'Yes' : 'No';
                }
            ],

            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
