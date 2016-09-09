<?php
    use yii\helpers\Html;
?>
Site new news <?=Html::a($model->title, ['news/view', 'id' => $model->id]);?>