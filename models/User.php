<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class User extends ActiveRecord implements IdentityInterface
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
     * Gets username (email).
     *
     * @return string username
     */
    public function getUsername(): string
    {
        return Yii::$app->user->identity->email;
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

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return User|IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * @return int|string current user ID
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
    }

    /**
     * Validates the password.
     *
     * @param string $password the password currently being validated.
     */
    public function validatePassword(string $password): bool
    {
        $hash = $this->password;

        return Yii::$app->getSecurity()->validatePassword($password, $hash);
    }
}
