<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="events-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'event_on')->dropDownList([ 'afterValidate' => 'AfterValidate', 'beforeValidate' => 'BeforeValidate', 'afterInsert' => 'AfterInsert', 'beforeInsert' => 'BeforeInsert', 'afterUpdate' => 'AfterUpdate', 'beforeUpdate' => 'BeforeUpdate', 'afterDelete' => 'AfterDelete', 'beforeDelete' => 'BeforeDelete', 'afterFind' => 'AfterFind', 'afterRefresh' => 'AfterRefresh', 'init' => 'Init', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'trigger_event_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_enabled')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
