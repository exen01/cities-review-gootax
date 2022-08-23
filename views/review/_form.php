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
?>

<div class="review-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

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
    </div>

    <?= $form->field($model, 'img')->fileInput(['class' => 'form-control-file', 'id' => 'inputImg01']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
