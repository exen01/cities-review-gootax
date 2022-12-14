<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReviewForm */
/* @var $modelFromDb app\models\Review */

$this->title = 'Update Review: ' . $modelFromDb->title;
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelFromDb->title, 'url' => ['view', 'id' => $modelFromDb->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="review-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
