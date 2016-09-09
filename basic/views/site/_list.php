<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
 
<blockquote>
    <h2><?= \Yii::$app->user->can('viewFullNews') ? Html::a(Html::encode($model->title), ['/news\view', 'id' => $model->id]) : Html::encode($model->title) ?></h2>
    <p><?= HtmlPurifier::process($model->preview_text) ?></p>
    <small><?=$model->user->username,' ',\Yii::$app->formatter->asDateTime($model->created_at);?></small>
</blockquote>