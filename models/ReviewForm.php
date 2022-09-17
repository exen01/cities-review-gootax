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
    public function saveReview(): bool
    {
        $review = new Review();
        $review->id_author = Yii::$app->user->id;
        $review->title = $this->title;
        $review->text = $this->text;
        $review->rating = $this->rating;
        $review->img = $this->img?->name;
        $review->date_create = time();
        $review->id_city = $this->city;

        return $review->save() && $this->uploadImg();
    }

    /**
     * Updates review with passed data.
     *
     * @param Review $review updated review
     * @return bool result of updating
     */
    public function updateReview(Review $review): bool
    {
        $review->title = $this->title;
        $review->text = $this->text;
        $review->rating = $this->rating;
        $review->img = $this->img?->name;
        $review->id_city = $this->city;

        return $review->save() && $this->uploadImg();
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

}