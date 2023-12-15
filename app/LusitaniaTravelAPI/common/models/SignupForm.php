<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */

class SignupForm extends Model
{
    public $username;
    public $password;
    public $repeatPassword;
    public $name;
    public $email;
    public $mobile;
    public $street;
    public $locale;
    public $postalCode;
    public $userType;
    public $terms;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['repeatPassword', 'required'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],

            [['name', 'mobile', 'street', 'locale', 'postalCode'], 'string'],
            [['name', 'street', 'locale'], 'string', 'max' => 255],
            ['mobile', 'string', 'length' => [9, 9]],
            ['postalCode', 'string', 'length' => [8, 10]],

            ['terms', 'required', 'requiredValue' => 1, 'message' => 'É necessário aceitar os termos e condições.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->status = 10;

            if (!$user->save()) {
                $transaction->rollBack();
            }

            // Criação do perfil associado ao utilizador
            $profile = new Profile();
            $profile->name = $this->name;
            $profile->mobile = $this->mobile;
            $profile->street = $this->street;
            $profile->locale = $this->locale;
            $profile->postalCode = $this->postalCode;
            $profile->user_id = $user->id;

            if (!$profile->save()) {
                $transaction->rollBack();
                return false;
            }

            // Adiciona a atribuição de função ao Profile
            $auth = Yii::$app->authManager;
            $clienteRole = $auth->getRole('cliente');

            if ($clienteRole !== null) {
                $auth->assign($clienteRole, $user->getId());

                // Adiciona o papel (role) ao Profile
                $profile->role = 'cliente';
                $profile->save();
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
        }
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    public function isRegistered()
    {
        // Lógica para verificar se o usuário já está registrado
        return User::find()->where(['username' => $this->username])->exists();
    }

}
