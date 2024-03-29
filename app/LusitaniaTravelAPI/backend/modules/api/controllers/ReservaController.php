<?php

namespace backend\modules\api\controllers;

use backend\libs\phpMQTT\phpMQTT;
use common\models\Confirmacao;
use common\models\Linhasreserva;
use common\models\Reserva;
use common\models\User;
use DateTime;
use frontend\models\Carrinho;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ReservaController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Reserva';

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
            throw new \yii\web\ForbiddenHttpException('Usuário não autenticado');
        }

        // Obtém o utilizador autenticado
        //$authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação
    }

    public function actionTaxareservas()
    {
        // Obtém todas as reservas confirmadas usando JOIN com a tabela Confirmacoes
        $reservasConfirmadas = Reserva::find()
            ->joinWith('confirmacoes') // Certifique-se de que você tem um método chamado `getConfirmacao()` em seu modelo Reserva
            ->where(['confirmacoes.estado' => 'Confirmado'])
            ->all();

        // Obtém todas as reservas (considerando todas as reservas, independentemente do estado)
        $todasAsReservas = Reserva::find()->all();

        // Calcula a taxa de reservas confirmadas
        $taxaReservasConfirmadas = count($reservasConfirmadas) / count($todasAsReservas) * 100;

        // Formata o valor para exibir até duas casas decimais
        $taxaReservasConfirmadasFormatada = number_format($taxaReservasConfirmadas, 2);

        return ['taxa_reservas' => $taxaReservasConfirmadasFormatada];
    }

    public function actionReservasconfirmadas()
    {
        $reservaModel = new $this->modelClass;
        $confirmacoes = Confirmacao::find()->where(['estado' => 'Confirmado'])->all();

        $reservasConfirmadas = [];
        foreach ($confirmacoes as $confirmacao) {
            $reserva = $reservaModel::findOne($confirmacao->reserva_id);
            if ($reserva) {
                $clienteNome = $this->getUserName($reserva->cliente_id);
                $funcionarioNome = $this->getUserName($reserva->funcionario_id);
                $fornecedorNome = $this->getFornecedorName($reserva->fornecedor_id);

                $reservasConfirmadas[] = [
                    'id' => $reserva->id,
                    'tipo' => $reserva->tipo,
                    'checkin' => $reserva->checkin,
                    'checkout' => $reserva->checkout,
                    'numeroquartos' => $reserva->numeroquartos,
                    'numeroclientes' => $reserva->numeroclientes,
                    'valor' => $reserva->valor,
                    'cliente_nome' => $clienteNome,
                    'funcionario_nome' => $funcionarioNome,
                    'fornecedor_nome' => $fornecedorNome,
                ];
            }
        }

        // Ordena as reservas confirmadas em ordem decrescente pela data de check-in
        usort($reservasConfirmadas, function ($a, $b) {
            return strtotime($b['checkin']) - strtotime($a['checkin']);
        });

        return $reservasConfirmadas;
    }

    private function getUserName($userId)
    {
        $user = User::findOne($userId);
        return ($user && $user->profile) ? $user->profile->name : null;
    }

    private function getFornecedorName($fornecedorId)
    {
        $fornecedor = \common\models\Fornecedor::findOne($fornecedorId);
        return $fornecedor ? $fornecedor->nome_alojamento : null;
    }

    public function actionConfirmarreserva($id)
    {
        $reservaModel = new $this->modelClass;

        try {
            $reserva = $reservaModel::findOne($id);

            if (!$reserva) {
                throw new NotFoundHttpException("Reserva com ID $id não encontrada.");
            }

            $confirmacao = $reserva->getConfirmacao()->one();

            if (!$confirmacao) {
                throw new ServerErrorHttpException('A reserva não possui uma confirmação associada.');
            }

            $confirmacao->estado = 'Confirmado';
            $confirmacao->dataconfirmacao = date('Y-m-d');

            if ($confirmacao->save()) {
                // Faz o publish MQTT quando a reserva é confirmada
                $this->FazPublishNoMosquitto('reserva', 'confirmada ' . json_encode(['reserva_id' => $id]));

                return [
                    'status' => 'success',
                    'message' => 'Reserva confirmada com sucesso.',
                    'confirmacao' => $confirmacao->attributes,
                    'reserva' => $reserva->attributes,
                ];
            } else {
                throw new ServerErrorHttpException('Erro ao salvar a confirmação.');
            }
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Erro interno do servidor.', 500, $e);
        }
    }

    public function actionCancelarreserva($id)
    {
        $reservaModel = new $this->modelClass;

        try {
            $reserva = $reservaModel::findOne($id);

            if (!$reserva) {
                throw new NotFoundHttpException("Reserva com ID $id não encontrada.");
            }

            $confirmacao = $reserva->getConfirmacao()->one();

            if (!$confirmacao) {
                throw new ServerErrorHttpException('A reserva não possui uma confirmação associada.');
            }

            $confirmacao->estado = 'Cancelado';
            $confirmacao->dataconfirmacao = date('Y-m-d');

            if ($confirmacao->save()) {
                // Faz o publish MQTT quando a reserva é cancelada
                $this->FazPublishNoMosquitto('reserva', 'cancelada ' . json_encode(['reserva_id' => $id]));

                return [
                    'status' => 'success',
                    'message' => 'Reserva cancelada com sucesso.',
                    'confirmacao' => $confirmacao->attributes,
                    'reserva' => $reserva->attributes,
                ];
            } else {
                throw new ServerErrorHttpException('Erro ao salvar a confirmação.');
            }
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Erro interno do servidor.', 500, $e);
        }
    }

    public function actionDetalhesreserva($id)
    {
        $reservaModel = new $this->modelClass;

        $reserva = $reservaModel::findOne($id);

        if (!$reserva) {
            throw new NotFoundHttpException("Reserva com ID $id não encontrada.");
        }

        $linhaReserva = Linhasreserva::findOne(['reservas_id' => $id]);
        $confirmacao = Confirmacao::findOne(['reserva_id' => $id]);

        // Obtém o nome do alojamento
        $fornecedorNome = $reserva->fornecedor->nome_alojamento;

        // Obtém os nomes do cliente e funcionário
        $clienteNome = $reserva->cliente->profile->name;
        $funcionarioNome = $reserva->funcionario->profile->name;

        // Obtém o nome do alojamento para a confirmação
        $fornecedorNomeConfirmacao = $confirmacao->fornecedor->nome_alojamento;

        return [
            'reserva' => [
                'id' => $reserva->id,
                'tipo' => $reserva->tipo,
                'checkin' => $reserva->checkin,
                'checkout' => $reserva->checkout,
                'numeroquartos' => $reserva->numeroquartos,
                'numeroclientes' => $reserva->numeroclientes,
                'valor' => $reserva->valor,
                'cliente_id' => $clienteNome,
                'funcionario_id' => $funcionarioNome,
                'fornecedor_id' => $fornecedorNome,
            ],
            'linha_reserva' => $linhaReserva ? $linhaReserva->attributes : null,
            'confirmacao' => $confirmacao ? [
                'id' => $confirmacao->id,
                'estado' => $confirmacao->estado,
                'dataconfirmacao' => $confirmacao->dataconfirmacao,
                'reserva_id' => $confirmacao->reserva_id,
                'fornecedor_id' => $fornecedorNomeConfirmacao,
            ] : null,
        ];
    }

    public function actionMostrarreserva($username)
    {
        // Obter o usuário logado
        $currentUser = Yii::$app->user->identity;

        // Verificar se o usuário logado corresponde ao username fornecido
        if ($currentUser->username !== $username) {
            throw new \yii\web\ForbiddenHttpException('Access denied.');
        }

        // Encontrar todas as reservas associadas ao cliente
        $reservas = Reserva::find()
            ->joinWith(['cliente.profile']) // Faz a junção com a tabela de perfil do user
            ->where(['profile.name' => $username])
            ->all();

        $dadosReservas = [];

        // Verificar se existem reservas
        if (!empty($reservas)) {
            foreach ($reservas as $reserva) {
                $mensagemReserva = 'Mostrando reserva para o cliente ' . $username;

                // Adicionar os atributos da reserva
                $dadosReserva = [
                    'id' => $reserva->id,
                    'tipo' => $reserva->tipo,
                    'checkin' => $reserva->checkin,
                    'checkout' => $reserva->checkout,
                    'numeroquartos' => $reserva->numeroquartos,
                    'numeroclientes' => $reserva->numeroclientes,
                    'valor' => $reserva->valor,
                    'cliente_id' => $reserva->cliente->profile->name,
                    'funcionario_id' => $reserva->funcionario->profile->name,
                    'fornecedor_id' => $reserva->fornecedor->nome_alojamento,
                    'estado' => $estado = $reserva->confirmacao ? $reserva->confirmacao->estado : null,
                ];

                $dadosReservas[] = $dadosReserva;
            }
        } else {
            $mensagemReserva = 'Nenhuma reserva criada no momento';
        }

        return [
            'message' => $mensagemReserva,
            'reservas' => $dadosReservas,
        ];
    }

    public function actionVerificar($reserva_id)
    {
        // Encontrar o modelo Reserva
        $reserva = Reserva::findOne($reserva_id);

        // Verificar se a reserva foi encontrada
        if ($reserva === null) {
            throw new BadRequestHttpException('Reserva não encontrada.');
        }

        $itensCarrinho = Carrinho::find()->where(['reserva_id' => $reserva->id])->all();

        $reserva->checkin = Yii::$app->request->post('checkin');
        $reserva->checkout = Yii::$app->request->post('checkout');
        $reserva->numeroclientes = Yii::$app->request->post('numeroclientes');
        $reserva->numeroquartos = Yii::$app->request->post('numeroquartos');
        $diasReserva = (new DateTime($reserva->checkout))->diff(new DateTime($reserva->checkin))->days;

        $total = 0;

        foreach ($itensCarrinho as $item) {
            $total += $diasReserva * $item->fornecedor->precopornoite * $reserva->numeroquartos;
            $total += $item->subtotal;

            $item->subtotal = $item->subtotal + $diasReserva * $item->fornecedor->precopornoite * $reserva->numeroquartos;
            $item->save();
        }

        $reserva->valor = $total;

        if (!$reserva->save()) {
            throw new BadRequestHttpException('Falha na verificação. Não foi possível salvar a reserva.');
        }

        $linhaReservas = []; // Inicializa o array de linhas de reserva

        for ($i = 0; $i < $reserva->numeroquartos; $i++) {
            $linhareserva = new Linhasreserva();
            $linhareserva->reservas_id = $reserva->id;

            // Verifica se a linha de reserva correspondente está presente no post
            if (isset(Yii::$app->request->post('linhasreservas')[$i])) {
                $linhaPost = Yii::$app->request->post('linhasreservas')[$i];
                $linhareserva->tipoquarto = $linhaPost['tipoquarto'];
                $linhareserva->numerocamas = $linhaPost['numerocamas'];
            }

            $linhareserva->numeronoites = $diasReserva;
            $linhareserva->subtotal = $reserva->valor / $diasReserva;
            $linhareserva->save();

            // Adiciona os atributos da linha de reserva ao array
            $linhaReservas[] = $linhareserva->attributes;
        }

        // Adiciona os atributos da reserva e do array de linhas de reserva ao retorno
        return [
            'message' => 'Verificação bem-sucedida!',
            'reserva' => $reserva->attributes,
            'linhasreservas' => $linhaReservas, // Corrigido para usar $linhaReservas
        ];
    }


    public function FazPublishNoMosquitto($canal, $msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; // set your username
        $password = ""; // set your password
        $client_id = "phpMQTT-publisher"; // unique!
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug.output", "Time out!");
        }
    }
}