<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$js =
    <<<JS
            $("#user-form").on("beforeSubmit", function () {
                let form = $(this);
                let data = form.serialize();
                
                // send data to server
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: data,
                    success: function (res){
                        $('#response').html(res);
                        if (!form.attr('action').includes('update')){
                            form[0].reset();
                        }
                    },
                    beforeSend: function() { $('#wait').show(); },
                    complete: function() { $('#wait').hide(); },
                    error: function() {
                        alert('Произошла ошибка при отправке данных!');
                    }
                });
                return false;
            });
JS;

$this->registerJs($js);
?>

<div class="user-form">

    <div id="response"></div>
    <div id="wait" style="display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 )
                url('http://i.stack.imgur.com/FhHRx.gif')
                50% 50%
                no-repeat;"></div>

    <?php $form = ActiveForm::begin([
            'id' => 'user-form'
    ]); ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_create')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
