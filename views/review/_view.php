<?php

use yii\helpers\Html;
use yii\helpers\Markdown;

/* @var $model app\models\Review */
?>

<div class="card mb-3">
    <div class="card-header">
        <?= Html::encode($model->title); ?>
    </div>
    <div class="card-body">
        <div class="card-title">
            Posted by <?= Yii::$app->user->isGuest ? Html::encode($model->author->fio) :
                $this->render('_authorDetails', [
                    'author' => $model->author,
                ]) ?>
        </div>
        <?php if ($model->img) {
            echo Html::img("@runtime/uploads/{$model->img}", ['class' => 'card-img-top', 'alt' => 'Card image cap']);
        } ?>
        <div class="card-body">
            <?= Markdown::process($model->text); ?>
        </div>
    </div>
    <div class="card-footer">
        <nav class="list-group list-group-horizontal">
            <div class="list-group-item">
                Rating: <?= Html::encode($model->rating); ?>
            </div>
            <?php if ($model->city) : ?>
                <div class="list-group-item">
                    City: <?= Html::encode($model->city->name); ?>
                </div>
            <?php else: ?>
                <div class="list-group-item">
                    City: All cities
                </div>
            <?php endif; ?>
            <div class="list-group-item">
                Created on <?= date('h:i A, F j, Y', $model->date_create); ?>
            </div>
            <?php if ($model->author->id === Yii::$app->user->id): ?>
                <div class="list-group-item">
                    <?= Html::a('Edit', ['review/update', 'id' => $model->id]) ?>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</div>

