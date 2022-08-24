<?php

/* @var $author app\models\User */

use yii\bootstrap4\Modal;
use yii\helpers\Html;

?>

<?php Modal::begin([
    'title' => Html::encode($author->fio),
    'toggleButton' => [
        'label' => Html::encode($author->fio),
        'tag' => 'a',
        'class' => 'card-link'
    ],
]) ?>

    <ul class="list-group list-group-flush">

        <li class="list-group-item">
            Phone number: <?= Html::a(Html::encode($author->phone), 'tel:' . Html::encode($author->phone)) ?>
        </li>
        <li class="list-group-item">
            Email: <?= Html::a(Html::encode($author->email), 'mailto:' . Html::encode($author->email)) ?>
        </li>
    </ul>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <?= Html::a('Show all reviews', ['review/index', 'id_author' => $author->id], ['class' => 'btn btn-primary']) ?>
    </div>
<?php Modal::end(); ?>