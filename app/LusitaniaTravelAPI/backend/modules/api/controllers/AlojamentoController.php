<?php

namespace backend\modules\api\controllers;
use backend\modules\api\components\CustomAuth;
use yii\filters\auth\HttpBasicAuth;

class AlojamentoController extends \yii\web\Controller
{
    public $user = null;
    public $modelClass = 'backend\models\Fornecedor';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(), // ou QueryParamAuth::className(),
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
        if($this->user)
        {
            if($this->user->id == 1)
            {
                if($action==="delete")
                {
                    throw new \yii\web\ForbiddenHttpException('Proibido');
                }
            }
        }
    }

    public function actionCount()
    {
        $alojamentosmodel = new $this->modelClass;
        $recs = $alojamentosmodel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
