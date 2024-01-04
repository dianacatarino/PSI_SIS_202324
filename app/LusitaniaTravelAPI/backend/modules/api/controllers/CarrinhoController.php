<?php

namespace backend\modules\api\controllers;

use common\models\Confirmacao;
use common\models\Reserva;
use common\models\User;
use frontend\models\Carrinho;
use common\models\Fornecedor;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class CarrinhoController extends ActiveController
{
    public $user = null;
    public $modelClass = 'frontend\models\Carrinho';

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
            $allowedRoles = ['admin', 'funcionario', 'fornecedor', 'cliente'];

            // Verifica se o usuário tem permissão para a ação específica
            if (in_array($userRole, $allowedRoles)) {
                // Negar acesso a ações customizadas para admin, funcionario e fornecedor
                if (in_array($action, ['Adicionarcarrinho', 'Limparcarrinho','Atualizarcarrinho'])) {
                    if (in_array($userRole, ['admin', 'funcionario', 'fornecedor'])) {
                        throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);
                    }
                }

                // O usuário tem permissão para todas as ações
                return;
            } elseif ($userRole === 'cliente' && in_array($action, ['create', 'update', 'delete'])) {
                // Usuários com papel 'cliente' têm permissão para criar, atualizar e excluir
                return;
            }
        }

        // Lança uma exceção se o usuário não estiver autenticado ou não tiver permissão
        throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);


        // Obtém o utilizador autenticado
        //$authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação
    }

    public function actionCalculartotal($nomecliente)
    {
        // Obtém todos os itens no carrinho para o user com o nome específico
        $itensCarrinho = Carrinho::find()
            ->joinWith(['cliente.profile']) // Faz a junção com a tabela de perfil do user
            ->where(['profile.name' => $nomecliente])
            ->all();

        $totalCalculado = 0;

        // Calcula o total somando os preços de cada item
        foreach ($itensCarrinho as $item) {
            $totalCalculado += $item->subtotal;
        }

        return ['total' => $totalCalculado];
    }

    public function actionAdicionarcarrinho($fornecedorId)
    {
        $fornecedor = Fornecedor::findOne($fornecedorId);

        if ($fornecedor === null) {
            throw new NotFoundHttpException('O fornecedor não foi encontrado.');
        }

        $clienteId = Yii::$app->user->identity->id;

        $funcionarioId = 45;

        // Cria uma nova reserva associada ao carrinho
        $reserva = new Reserva([
            'tipo' => 'Online',
            'checkin' => '0000-00-00',
            'checkout' => '0000-00-00',
            'numeroquartos' => 0,
            'numeroclientes' => 0,
            'valor' => 0,
            'cliente_id' => $clienteId,
            'fornecedor_id' => $fornecedorId,
            'funcionario_id' => $funcionarioId,
        ]);

        // Salva a reserva
        $reserva->save();

        // Obtém o ID da reserva recém-criada
        $reservaId = $reserva->id;

        // Cria uma nova confirmação associada à reserva com o estado "pendente"
        $confirmacao = new Confirmacao([
            'reserva_id' => $reservaId,
            'estado' => 'Pendente',
            'dataconfirmacao' => '0000-00-00',
            'fornecedor_id' => $fornecedorId,
        ]);

        // Salva a confirmação
        $confirmacao->save();

        // Cria um novo item no carrinho associado à reserva
        $carrinhoExistente = new Carrinho([
            'fornecedor_id' => $fornecedorId,
            'cliente_id' => $clienteId,
            'quantidade' => 1,
            'preco' => $fornecedor->precopornoite,
            'subtotal' => 0,
            'reserva_id' => $reservaId,
        ]);

        // Salva o item no carrinho
        $carrinhoExistente->save();

        // Retorna uma resposta adequada (por exemplo, uma mensagem de sucesso ou detalhes do carrinho)
        return [
            'message' => 'Item adicionado ao carrinho com sucesso.',
            'data' => $carrinhoExistente->attributes, // You can modify this based on what data you want to include
        ];
    }


    public function actionLimparcarrinho($fornecedorId)
    {
        // Obtém o ID do cliente atualmente autenticado
        $clienteId = Yii::$app->user->identity->id;

        // Encontra todos os itens no carrinho para o cliente e fornecedor específicos
        $itensCarrinho = Carrinho::find()
            ->where(['cliente_id' => $clienteId, 'fornecedor_id' => $fornecedorId])
            ->all();

        // Remove cada item do carrinho
        foreach ($itensCarrinho as $item) {
            $item->delete();
        }

        return ['message' => 'Carrinho limpo com sucesso para o fornecedor ID ' . $fornecedorId];
    }

    public function actionAtualizarcarrinho($fornecedorId)
    {
        // Verifica se o fornecedor existe
        $fornecedor = Fornecedor::findOne($fornecedorId);

        if (!$fornecedor) {
            throw new NotFoundHttpException('Fornecedor não encontrado.');
        }

        // Lógica para atualizar o preço do carrinho com base no novo preço do fornecedor
        $novosItensCarrinho = Carrinho::find()
            ->andWhere(['fornecedor_id' => $fornecedorId])
            ->all();

        foreach ($novosItensCarrinho as $item) {
            // Atualiza o preço do carrinho com o novo preço do fornecedor
            $item->preco = $fornecedor->precoPorNoite;
            $item->subtotal = $item->quantidade * $item->preco;

            // Salva as alterações no item do carrinho
            $item->save();
        }

        return [
            'message' => 'Item adicionado ao carrinho com sucesso.',
        ];
    }



}
