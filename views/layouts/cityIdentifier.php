<?php
/* @var $city string */

use yii\helpers\Html;

?>

<?php if (!Yii::$app->session->get('user_city') && !empty($city)): ?>
    <div class="alert alert-primary" role="alert">
        <?= "Is {$city} your city?" ?>
        <?= Html::a('Yes', ['site/city', 'city' => $city], ['class' => 'btn btn-success']) ?>
        <?= Html::a('No', ['site/city'], ['class' => 'btn btn-danger']) ?>
    </div>
<?php endif; ?>