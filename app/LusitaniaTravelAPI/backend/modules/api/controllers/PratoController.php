<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class PratoController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Pratos';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(), // ou QueryParamAuth::className(),
            //’except' => ['index', 'view'], //Excluir aos GETs
            //'auth' => [$this, 'auth']
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

        // Proibir todas as outras ações GET
        if (\Yii::$app->request->isGet) {
            throw new \yii\web\ForbiddenHttpException('Proibido');
        }
        // Obtém o utilizador autenticado
        $authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação

        // Proíbe a ação DELETE ao utilizador da API de id 2
        if ($action === 'delete' && $authenticatedUser && $authenticatedUser->id == 2) {
            throw new \yii\web\ForbiddenHttpException('Proibido');
        }
    }

    public function actionCount()
    {
        $pratosmodel = new $this->modelClass;
        $recs = $pratosmodel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionNomes()
    {
        $pratosmodel = new $this->modelClass;
        $recs = $pratosmodel::find()->select(['nome'])->all();
        return $recs;
    }

    public function actionPreco($id)
    {
        $pratosmodel = new $this->modelClass;
        //$recs = $pratosmodel::find()->select(['preco'])->where(['id' => $id])->all(); //array
        $recs = $pratosmodel::find()->select(['preco'])->where(['id' => $id])->one(); //objeto json
        return $recs;
    }

    public function actionPrecopornome($nomeprato)
    {
        $pratosmodel = new $this->modelClass;
        $recs = $pratosmodel::find()->select(['preco'])->where(['nome' => $nomeprato])->all(); //array
        return $recs;
    }

    public function actionDelpornome($nomeprato)
    {
        $climodel = new $this->modelClass;
        $recs = $climodel::deleteAll(['nome' => $nomeprato]);
        return $recs;
    }

    public function actionPutprecopornome($nomeprato)
    {
        $novo_preco=\Yii::$app->request->post('preco');
        $climodel = new $this->modelClass;
        $ret = $climodel::findOne(['nome' => $nomeprato]);
        if($ret)
        {
            $ret->preco = $novo_preco;
            $ret->save();
        }
        else
        {
            throw new \yii\web\NotFoundHttpException("Nome de prato não existe");
        }
    }

    public function actionPostpratovazio()
    {
        $pratomodel = new $this->modelClass;
        $pratomodel->id=0; //é autonumber!
        $pratomodel->nome=' ';
        $pratomodel->descricao=' ';
        $pratomodel->preco=0;
        $pratomodel->disponivel=0;
        $pratomodel->save();
        return $pratomodel;
    }

    public function actionDatacriacaoprato($ano, $mes, $dia)
    {
        $data = "$ano-$mes-$dia";
        $pratos = $this->modelClass::find()->where(['>=', 'data_criação', $data])->all();
        return $pratos;
    }
}