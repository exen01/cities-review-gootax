<?php

use app\models\City;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Review */
/* @var $form yii\widgets\ActiveForm */
/* @var $cities array */

$js =
    <<<JS
            $("#reviewForm").on("beforeSubmit", function () {
                let form = $(this);
                let data = new FormData(reviewForm);
                let img = form.find('input[name="ReviewForm[img]"]');
                
                if (img.length > 0){
                    data.append("img", img[1].files[0]);
                }
                
                // send data to server
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    contentType: false,
	                processData: false,
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

<div class="review-form">

    <!-- Display response -->
    <div id="response"></div>
    <!-- Loading animation on ajax request -->
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
        'id' => 'reviewForm',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'rating')->dropDownList([
        '1' => 'One Star',
        '2' => 'Two Star',
        '3' => 'Three Star',
        '4' => 'Four Star',
        '5' => 'Five Star',
    ], ['prompt' => 'Select Rating']) ?>

    <div class="form-group">
        <label class="control-label" for="cityAutoComplete">City</label>
        <?php
        $cities = City::find()
            ->select(['name as value', 'name as  label', 'id as id'])
            ->asArray()
            ->all();

        echo AutoComplete::widget([
            'options' => [
                'id' => 'cityAutoComplete',
                'class' => 'form-control',
                'placeholder' => 'Empty for all cities...',
            ],
            'clientOptions' => [
                'source' => $cities,
                'autoFill' => true,
                'minLength' => '3',
                'select' => new JsExpression("function(event, ui) {
			        $('#reviewform-city').val(ui.item.id);
			     }"),
            ],
        ]);
        ?>
        <div class="help-block"></div>

        <?= Html::activeHiddenInput($model, 'city') ?>

        <?php if ($model->city) {
            foreach ($cities as $city) {
                if ($model->city === $city['id']) {
                    $cityName = $city['value'];
                    echo "<div>$cityName</div>";
                }
            }
        } ?>
    </div>

    <?= $form->field($model, 'img')->fileInput(['class' => 'form-control-file', 'id' => 'inputImg01']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
