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
use yii\web\ServerErrorHttpException;

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
        if ($user && $user->validatePassword($password)) {
            $this->user = $user; //Guardar user autenticado
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
            throw new \yii\web\ForbiddenHttpException('User não autenticado');
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

    public function actionDetalhesalojamento($id)
    {
        // Encontrar o fornecedor com o ID fornecido
        $fornecedor = Fornecedor::findOne($id);

        if (!$fornecedor) {
            throw new NotFoundHttpException("Nenhum fornecedor encontrado com o ID '{$id}'.");
        }

        // Obter detalhes do fornecedor
        $detalhesAlojamento = $fornecedor->toArray();

        // Adicionar imagens associadas ao fornecedor
        $detalhesAlojamento['imagens'] = $fornecedor->imagens;

        return $detalhesAlojamento;
    }

    public function actionCountportipoelocalizacao($tipo, $localizacao_alojamento)
    {
        $fornecedormodel = new $this->modelClass;

        // Obter todos os fornecedores do tipo e localização específicos
        $fornecedores = $fornecedormodel::find()
            ->where(['tipo' => $tipo, 'localizacao_alojamento' => $localizacao_alojamento])
            ->all();

        // Contar o número de fornecedores
        $count = count($fornecedores);

        return [
            'count' => $count,
            'tipo' => $tipo,
            'localizacao_alojamento' => $localizacao_alojamento,
            'fornecedores' => $fornecedores,
        ];
    }


    public function actionTipo($tipo)
    {
        $fornecedorModel = new $this->modelClass;

        // Substitua 'tipo' pelo nome do atributo de tipo em seu modelo de fornecedor.
        $fornecedores = $fornecedorModel::find()->where(['tipo' => $tipo])->all();

        if (!$fornecedores) {
            throw new NotFoundHttpException("Nenhum fornecedor encontrado para o tipo '{$tipo}'.");
        }

        return $fornecedores;
    }

    public function actionLocalizacao($localizacao_alojamento)
    {
        $fornecedorModel = new $this->modelClass;

        // Substitua 'localizacao' pelo nome do atributo de localização em seu modelo de fornecedor.
        $fornecedores = $fornecedorModel::find()->where(['localizacao_alojamento' => $localizacao_alojamento])->all();

        if (!$fornecedores) {
            throw new NotFoundHttpException("Nenhum fornecedor encontrado para a localização '{$localizacao_alojamento}'.");
        }

        $fornecedoresComImagens = [];

        // Para cada fornecedor, obtém as imagens associadas
        foreach ($fornecedores as $fornecedor) {
            $fornecedorComImagens = $fornecedor->toArray();
            $fornecedorComImagens['imagens'] = $fornecedor->imagens;

            $fornecedoresComImagens[] = $fornecedorComImagens;
        }

        return $fornecedoresComImagens;
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

    public function actionFavoritos()
    {
        // Verifica se o usuário está logado
        if (!Yii::$app->user->isGuest) {
            // Obtém o perfil do usuário logado
            $profile = Yii::$app->user->identity->profile;

            // Obtém a lista de favoritos do usuário
            $favoritos = json_decode($profile->favorites, true);

            if (empty($favoritos)) {
                return ['favoritos' => []];
            }

            // Obtém os detalhes dos fornecedores favoritos, incluindo imagens
            $fornecedoresFavoritos = Fornecedor::find()
                ->with('imagens') // Carregar relação imagens
                ->where(['id' => $favoritos])
                ->asArray()
                ->all();

            // Retorna a lista de fornecedores favoritos
            return ['favoritos' => $fornecedoresFavoritos];
        } else {
            throw new \yii\web\ForbiddenHttpException('User não autenticado.');
        }
    }

    public function actionAdicionarfavorito($fornecedorId)
    {
        // Verifica se o usuário está logado
        if (!Yii::$app->user->isGuest) {
            // Verifica se o fornecedor com o ID fornecido existe
            $fornecedor = Fornecedor::find()
                ->with('imagens') // Carregar relação imagens
                ->where(['id' => $fornecedorId])
                ->asArray()
                ->one();

            if (!$fornecedor) {
                throw new NotFoundHttpException("Nenhum fornecedor encontrado com o ID '{$fornecedorId}'.");
            }

            // Obtém o perfil do usuário logado
            $profile = Yii::$app->user->identity->profile;

            // Obtém a lista de favoritos do usuário
            $favoritos = json_decode($profile->favorites, true);

            // Adiciona o fornecedor à lista de favoritos se ainda não estiver lá
            if (!in_array($fornecedorId, $favoritos)) {
                $favoritos[] = $fornecedorId;

                // Atualiza a lista de favoritos no perfil do usuário
                $profile->favorites = json_encode($favoritos);

                if ($profile->save()) {
                    return ['message' => 'Fornecedor adicionado aos favoritos com sucesso.', 'fornecedor' => $fornecedor];
                } else {
                    throw new ServerErrorHttpException('Erro ao salvar os favoritos do usuário.');
                }
            } else {
                return ['message' => 'O fornecedor já está na lista de favoritos.'];
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('User não autenticado.');
        }
    }

    public function actionRemoverfavorito($fornecedorId)
    {
        // Verifica se o usuário está logado
        if (!Yii::$app->user->isGuest) {
            // Verifica se o fornecedor com o ID fornecido existe
            $fornecedor = Fornecedor::find()
                ->with('imagens') // Carregar relação imagens
                ->where(['id' => $fornecedorId])
                ->asArray()
                ->one();

            if (!$fornecedor) {
                throw new NotFoundHttpException("Nenhum fornecedor encontrado com o ID '{$fornecedorId}'.");
            }

            // Obtém o perfil do usuário logado
            $profile = Yii::$app->user->identity->profile;

            // Obtém a lista de favoritos do usuário
            $favoritos = json_decode($profile->favorites, true);

            // Remove o fornecedor da lista de favoritos se estiver lá
            $key = array_search($fornecedorId, $favoritos);
            if ($key !== false) {
                unset($favoritos[$key]);

                // Atualiza a lista de favoritos no perfil do usuário
                $profile->favorites = json_encode($favoritos);

                if ($profile->save()) {
                    return ['message' => 'Fornecedor removido dos favoritos com sucesso.', 'fornecedor' => $fornecedor];
                } else {
                    throw new ServerErrorHttpException('Erro ao salvar os favoritos do usuário.');
                }
            } else {
                return ['message' => 'O fornecedor não está na lista de favoritos.'];
            }
        } else {
            throw new \yii\web\ForbiddenHttpException('User não autenticado.');
        }
    }



}