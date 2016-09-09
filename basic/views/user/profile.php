<?php
    use yii\helpers\Html;
?>
    <h2>Receive</h2>
    <?php $form = \yii\widgets\ActiveForm::begin([
    'method' => 'post',
]); 

    if (!empty($data)) {
        foreach ($data as $type => $status) {
            echo Html::checkbox($type, $status),' ',$names[$type],'<br>';
        }
    }
?>
        <button class="btn btn-success">Save</button>
    <?php $form->end(); ?>