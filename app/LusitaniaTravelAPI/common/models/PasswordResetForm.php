<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;

class PasswordResetForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'validateEmail'],
        ];
    }

    /**
     * Custom validation to check if the email exists in the database.
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(['email' => $this->email]);

            if (!$user) {
                $this->addError($attribute, 'Não há utilizador registrado com este endereço de e-mail.');
            }
        }
    }

    /**
     * Sends a password reset email.
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        if ($this->validate()) {
            $user = User::findOne(['email' => $this->email]);

            if ($user) {
                $user->generatePasswordResetToken();
                if ($user->save()) {
                    return Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                        ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' robot'])
                        ->setTo($this->email)
                        ->setSubject('Password reset for ' . Yii::$app->name)
                        ->send();
                }
            }
        }

        return false;
    }
}

