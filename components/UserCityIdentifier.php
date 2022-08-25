<?php

namespace app\components;

use Yii;
use yii\base\Widget;

class UserCityIdentifier extends Widget
{
    public string $city = '';

    public function init()
    {
        // set user IP in the URL
        $geoData = json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip='), true);
        if ($geoData['geoplugin_status'] === 200 && $geoData['geoplugin_city']) {
            $this->city = $geoData['geoplugin_city'];
            Yii::$app->session->set('is_user_city_defined', true);
        }
    }

    public function run()
    {
        return $this->render('//layouts/cityIdentifier', ['city' => $this->city]);
    }

}