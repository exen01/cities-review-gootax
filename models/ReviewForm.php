<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ReviewForm extends Model
{
    public $title;
    public $text;
    public $city;
    public $rating;
    /**
     * @var UploadedFile
     */
    public $img;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['rating', 'integer'],
            [['title', 'text', 'rating'], 'required'],
            [['title'], 'string', 'max' => 100],
            [['text'], 'string', 'max' => 255],
            ['city', 'string'],
            [['img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'title' => 'Title',
            'text' => 'Text',
            'rating' => 'Rating',
            'img' => 'Image',
            'city' => 'City',
        ];
    }

    /**
     * Creates and fill review with passed data.
     *
     * @return bool result of save
     */
    public function saveReview(string $cityName = null): bool
    {
        $review = new Review();
        $review->id_author = Yii::$app->user->id;
        $review->title = $this->title;
        $review->text = $this->text;
        $review->rating = $this->rating;
        $review->img = $this->img?->name;
        $review->date_create = time();
        if ($this->city) {
            $review->id_city = $this->city;
        } elseif ($cityName) {
            $review->id_city = $this->addCity($cityName);
        }

        return $review->validate() && $review->save() && $this->uploadImg();
    }

    /**
     * Updates review with passed data.
     *
     * @param Review $review updated review
     * @return bool result of updating
     */
    public function updateReview(Review $review, string $cityName = null): bool
    {
        $review->title = $this->title;
        $review->text = $this->text;
        $review->rating = $this->rating;
        $review->img = $this->img?->name;
        if ($this->city) {
            $review->id_city = $this->city;
        } elseif ($cityName) {
            $review->id_city = $this->addCity($cityName);
        }

        return $review->validate() && $review->save() && $this->uploadImg();
    }

    /**
     * Upload the image to disk.
     *
     * @return bool result of uploading
     */
    public function uploadImg(): bool
    {
        if ($this->validate() && $this->img) {
            return $this->img->saveAs('@runtime/uploads/' . $this->img->baseName . '.' . $this->img->extension);
        } else {
            return true;
        }
    }

    /**
     * Finds cities by passed city name.
     * Takes the first city in the search results and saves it to database.
     * If the city is not found or saved unsuccessfully, then returns null.
     *
     * @param string $cityName searched city name
     * @return int|null id of saved city or null
     */
    private function addCity(string $cityName): ?int
    {
        $geoData = json_decode(file_get_contents("https://search-maps.yandex.ru/v1/?text=$cityName&type=geo&lang=en_RU&apikey=<your api key>"), true);

        if ($geoData['features']) {
            $firstFoundCityName = $geoData['features']['0']['properties']['name'];

            $city = new City();
            $city->name = $firstFoundCityName;
            $city->date_create = time();
            if ($city->validate() && $city->save()) {
                return $city->id;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}