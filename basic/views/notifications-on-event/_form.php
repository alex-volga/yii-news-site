<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NotificationsOnEvent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notifications-on-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'event_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notify_type')->dropDownList(array_combine(\app\models\Notifications::getAllList(), \app\models\Notifications::getAllList())) ?>
    
    <?= $form->field($model, 'rolesAsArray')->dropDownList(webvimark\modules\UserManagement\models\rbacDB\Role::getAvailableRoles(true, true), ['multiple' => 'multiple']); ?>
    
    <div class="alert alert-info">
      <strong>Info!</strong>To substitute the values using the syntax {%model_property%} or {%model_property::submodel_property%} of some model.
    </div>
    
    <?= $form->field($model, 'tpl_subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tpl_body')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
