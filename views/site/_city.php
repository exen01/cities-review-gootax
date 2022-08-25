<?php
/* @var $model app\models\City */

use yii\helpers\Html;

?>

<li class="list-group-item">
    <?= Html::a($model->name, ['site/city', 'city' => $model->name]) ?>
</li>
