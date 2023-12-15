<?php

namespace backend\modules\api\controllers;

use common\models\Avaliacao;
use common\models\Comentario;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class FornecedorController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Fornecedor';

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
            //$this->user=$user; //Guardar user autenticado
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('No authentication'); //403
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        // Certifique-se de que $this->user foi definido durante a autenticação
        if ($this->user) {
            $allowedUsernames = ['admin', 'fornecedor', 'funcionario'];

            if (in_array($this->user->username, $allowedUsernames)) {
                // Permite ações de criar, alterar e excluir
                return;
            }

            // Restringe as ações de criar, alterar e excluir
            if (in_array($action, ['create', 'update', 'delete'])) {
                throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);
            }
        }


        // Obtém o utilizador autenticado
        //$authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação
    }

    public function actionCount()
    {
        $fornecedormodel = new $this->modelClass;
        $recs = $fornecedormodel::find()->all();
        return ['count' => count($recs)];
    }


    public function actionFornecedorportipo($tipo)
    {
        $fornecedorModel = new $this->modelClass;

        // Substitua 'tipo' pelo nome do atributo de tipo em seu modelo de fornecedor.
        $fornecedores = $fornecedorModel::find()->where(['tipo' => $tipo])->all();

        if (!$fornecedores) {
            throw new NotFoundHttpException("Nenhum fornecedor encontrado para o tipo '{$tipo}'.");
        }

        return $fornecedores;
    }

    public function actionFornecedorporlocalizacao($localizacao_alojamento)
    {
        $fornecedorModel = new $this->modelClass;

        // Substitua 'localizacao' pelo nome do atributo de localização em seu modelo de fornecedor.
        $fornecedores = $fornecedorModel::find()->where(['localizacao_alojamento' => $localizacao_alojamento])->all();

        if (!$fornecedores) {
            throw new NotFoundHttpException("Nenhum fornecedor encontrado para a localização '{$localizacao_alojamento}'.");
        }

        return $fornecedores;
    }

    public function actionComentariospordata($id, $data)
    {
        if (!strtotime($data)) {
            throw new BadRequestHttpException('Formato de data inválido.');
        }

        $dataFormatada = date('Y-m-d', strtotime($data));

        $query = Comentario::find()
            ->where(['fornecedor_id' => $id, 'data_comentario' => $dataFormatada]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }

    public function actionAvaliacoesmedia($id)
    {
        $avaliacoes = Avaliacao::find()->where(['fornecedor_id' => $id])->all();

        if (!empty($avaliacoes)) {
            // Calcule a média das avaliações
            $notas = array_column($avaliacoes, 'classificacao');
            $avaliacaoMedia = count($notas) > 0 ? array_sum($notas) / count($notas) : 0;

            return ['avaliacao_media' => $avaliacaoMedia];
        } else {
            throw new \yii\web\NotFoundHttpException("Nenhuma avaliação encontrada para o alojamento com ID '$id'.");
        }
    }

}

