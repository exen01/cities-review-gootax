<?php

/** @var $this yii\web\View */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\widgets\ListView;

$this->title = 'My Yii Application';
?>

<div class="jumbotron text-center bg-transparent">
    <h1 class="display-4">Hello!</h1>
    <p class="lead">Welcome to cities review site.</p>

    <?php if (!Yii::$app->session->get('is_user_city_defined')) : ?>
        <h2>Select your city from the list</h2>
        <ul class="list-group list-group-flush">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_city',
                'summary' => '',
            ]); ?>
        </ul>
    <?php endif; ?>
</div>
