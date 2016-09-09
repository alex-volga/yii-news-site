<?php
use yii\helpers\Html;
use yii\widgets\ListView;/* @var $this yii\web\View */

$this->title = 'News';
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= \Yii::$app->user->can('addNews') ? Html::a('Create News', ['/news\create'], ['class' => 'btn btn-success']) : '' ?>
    </p>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_list'
    ]) ?>
</div>