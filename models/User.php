<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $fio
 * @property string $email
 * @property string|null $phone
 * @property int $date_create
 * @property string $password
 *
 * @property Review[] $reviews
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fio', 'email', 'date_create', 'password'], 'required'],
            [['date_create'], 'integer'],
            [['fio', 'email', 'phone', 'password'], 'string', 'max' => 128],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'fio' => 'Fio',
            'email' => 'Email',
            'phone' => 'Phone',
            'date_create' => 'Date Create',
            'password' => 'Password',
        ];
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['id_author' => 'id']);
    }
}
