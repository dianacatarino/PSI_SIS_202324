<?php

namespace backend\modules\api\controllers;

use common\models\Avaliacao;
use common\models\Comentario;
use common\models\Fornecedor;
use common\models\Imagem;
use Yii;
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
            } elseif ($userRole === 'cliente' && in_array($action, ['create', 'update', 'delete'])) {
                // Usuários com papel 'cliente' não têm permissão para criar, atualizar e excluir
                throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);
            }
            // Permite ações de leitura (GET) para todos os usuários, incluindo clientes
        } else {
            // Lança uma exceção se o usuário não estiver autenticado
            throw new \yii\web\ForbiddenHttpException('Usuário não autenticado');
        }

        // Obtém o utilizador autenticado
        //$authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação
    }

    public function actionAlojamentos()
    {
        // Obtém todos os fornecedores
        $fornecedores = Fornecedor::find()->all();

        $fornecedoresComImagens = [];

        // Para cada fornecedor, obtém as imagens associadas
        foreach ($fornecedores as $fornecedor) {
            $fornecedorComImagens = $fornecedor->toArray();
            $fornecedorComImagens['imagens'] = $fornecedor->imagens;

            $fornecedoresComImagens[] = $fornecedorComImagens;
        }

        return $fornecedoresComImagens;
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
            ->select(['id', 'titulo', 'descricao', 'data_comentario', 'cliente_id', 'fornecedor_id'])
            ->with(['cliente', 'fornecedor']) // Carregar relações cliente e fornecedor
            ->where(['fornecedor_id' => $id, 'data_comentario' => $dataFormatada]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $comentarios = $dataProvider->getModels();

        // Mapear resultados para incluir nome do cliente e do fornecedor
        $resultados = array_map(function ($comentario) {
            return [
                'id' => $comentario->id,
                'titulo' => $comentario->titulo,
                'descricao' => $comentario->descricao,
                'data_comentario' => $comentario->data_comentario,
                'cliente_nome' => $comentario->cliente->profile->name,
                'fornecedor_nome' => $comentario->fornecedor->nome_alojamento,
            ];
        }, $comentarios);

        return $resultados;
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

