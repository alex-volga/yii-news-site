<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;

$this->title = 'Send message';
$this->params['breadcrumbs'][] = $this->title;

$css = '#sendmessageform-users {max-height: 200px;overflow: auto;}';

$js = 'function onStateChange() {
    $("#roles-block").hide();
    $("#users-block").hide();
    switch (this.value) {
        case "1":
            $("#roles-block").show();
            break;
            
        case "2":
            $("#users-block").show();
            break;
    }
}

$("[type=radio]").change(onStateChange);
';
$this->registerJs($js, View::POS_READY);
$this->registerCss($css);
?>
<div class="send-message">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'send-message-form',
    ]); ?>
    
    <div class="alert alert-info">
      <strong>Info!</strong> Subject and message is a template. To substitute the values using the syntax {%model_property%} or {%model_property::submodel_property%} of User model.
    </div>

        <?= $form->field($model, 'subject')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'message')->textArea(['rows' => '3']) ?>
        
        <?= $form->field($model, 'state')->radioList(\app\models\SendMessageForm::$stateList) ?>
        
        <span id="roles-block" style="display:none">
        <?= $form->field($model, 'roles[]')->dropDownList(webvimark\modules\UserManagement\models\rbacDB\Role::getAvailableRoles(true, true), ['multiple' => 'multiple']); ?>
        </span>
        
        <span id="users-block" style="display:none">
        <?= $form->field($model, 'users')->checkboxList(ArrayHelper::map(\app\models\User::find()->all(), 'id', 'username')); ?>
        </span>
        
        <div class="form-group">
            <div>
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'send-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
