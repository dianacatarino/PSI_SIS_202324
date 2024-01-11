<?php

namespace backend\modules\api\controllers;

use common\models\Avaliacao;
use common\models\Comentario;
use common\models\Fornecedor;
use common\models\Imagem;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class UserController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(), // ou QueryParamAuth::className(),
            //’except' => ['index', 'view'], //Excluir aos GETs
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }

    public function auth($username, $password)
    {
        $user = \common\models\User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            $this->user=$user; //Guardar user autenticado
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('No authentication'); //403
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        // Certifique-se de que $this->user foi definido durante a autenticação
        if ($this->user && Yii::$app->user->identity) {
            // Obtém o papel do perfil do usuário
            $userRole = Yii::$app->user->identity->profile->role;

            // Define os papéis permitidos para acessar ações de criar, atualizar e excluir
            $allowedRoles = ['admin', 'funcionario', 'fornecedor'];

            // Verifica se o usuário tem permissão para a ação específica
            if (in_array($userRole, $allowedRoles)) {
                // O usuário tem permissão para todas as ações
                return;
            } elseif ($userRole === 'cliente' && in_array($action, ['delete'])) {
                // Usuários com papel 'cliente' não têm permissão para criar, atualizar e excluir
                throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);
            }
            // Permite ações de leitura (GET) para todos os usuários, incluindo clientes
        } else {
            // Lança uma exceção se o usuário não estiver autenticado
            throw new \yii\web\ForbiddenHttpException('User não autenticado');
        }

        // Obtém o utilizador autenticado
        //$authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação
    }

    public function actionLogin($username, $password)
    {
        $user = $this->auth($username, $password);

        return [
            'status' => 'success',
            'message' => 'Login efetuado com sucesso.',
            'access_token' => $user->getAuthKey(),
            'token_type' => 'bearer',
        ];
    }

    public function actionRegister()
    {
        try {
            // Get request data from the body
            $requestData = Yii::$app->getRequest()->getBodyParams();

            // Extract user data
            $user = $requestData['user'] ?? [];
            $username = $user['username'] ?? null;
            $email = $user['email'] ?? null;
            $password = $user['password'] ?? null;

            // Extract profile data
            $profile = $requestData['profile'] ?? [];
            $name = $profile['name'] ?? null;
            $mobile = $profile['mobile'] ?? null;
            $street = $profile['street'] ?? null;
            $locale = $profile['locale'] ?? null;
            $postalCode = $profile['postalCode'] ?? null;

            // Create a new instance of the User model and assign values
            $userModel = new User([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'status' => 10, // Set status to 'active
                'auth_key' => Yii::$app->security->generateRandomString(), // Generate auth key
                'verification_token' => Yii::$app->security->generateRandomString(), // Generate verification token
            ]);

            // Validate and save the user
            if (!$userModel->validate() || !$userModel->save()) {
                throw new BadRequestHttpException('Falha ao criar o usuário.');
            }

            // Create a new instance of the Profile model and assign values
            $profileModel = new Profile([
                'name' => $name,
                'mobile' => $mobile,
                'street' => $street,
                'locale' => $locale,
                'postalCode' => $postalCode,
                'role' => 'cliente',
                'user_id' => $userModel->id,
            ]);

            // Validate and save the profile
            if (!$profileModel->validate() || !$profileModel->save()) {
                throw new BadRequestHttpException('Falha ao criar o perfil.');
            }

            $userAttributes = $userModel->attributes;
            $profileAttributes = $profileModel->attributes;
            Yii::$app->response->statusCode = 201; // HTTP status code for resource created successfully
            return [
                'status' => 'success',
                'message' => 'User registrado com sucesso.',
                'data' => [
                    'user' => $userAttributes,
                    'profile' => $profileAttributes,
                ],
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500; // HTTP status code for internal server error
            return [
                'status' => 'error',
                'message' => 'Erro ao processar o registro.',
            ];
        }
    }

    public function actionMostraruser($username)
    {
        try {
            // Get the currently logged-in user
            $currentUser = Yii::$app->user->identity;

            // Check if the provided username matches the logged-in user's username
            if ($currentUser->username !== $username) {
                throw new \yii\web\ForbiddenHttpException('Access denied.');
            }

            $userDetails = [
                'username' => $currentUser->username,
                'email' => $currentUser->email,
                'name' => $currentUser->profile->name,
                'mobile' => $currentUser->profile->mobile,
                'street' => $currentUser->profile->street,
                'locale' => $currentUser->profile->locale,
                'postalCode' => $currentUser->profile->postalCode,
            ];

            return [
                'status' => 'success',
                'data' => $userDetails,
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500; // HTTP status code for internal server error
            return [
                'status' => 'error',
                'message' => 'Erro ao recuperar os dados do user.',
            ];
        }
    }
}

