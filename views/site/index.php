<?php

/** @var $this yii\web\View */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\widgets\ListView;

$this->title = 'My Yii Application';
?>
<h2>Select your city from the list</h2>
<div class="list-group">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_city',
        'summary' => '',
    ]); ?>
</div>
