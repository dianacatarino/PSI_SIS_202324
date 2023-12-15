<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string $name
 * @property string $mobile
 * @property string $street
 * @property string $locale
 * @property string $postalCode
 * @property string|null $role
 * @property int $user_id
 * @property string|null $favorites
 *
 * @property User $user
 * @property User[] $users
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'mobile', 'street', 'locale', 'postalCode', 'user_id'], 'required'],
            [['role', 'favorites'], 'string'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 25],
            [['mobile'], 'string', 'max' => 9],
            [['street'], 'string', 'max' => 30],
            [['locale'], 'string', 'max' => 20],
            [['postalCode'], 'string', 'max' => 10],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'street' => 'Street',
            'locale' => 'Locale',
            'postalCode' => 'Postal Code',
            'role' => 'Role',
            'user_id' => 'User ID',
            'favorites' => 'Favorites',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['profile_id' => 'id']);
    }
}
