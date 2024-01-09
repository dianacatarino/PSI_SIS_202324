<?php

namespace backend\modules\api\controllers;

use common\models\Confirmacao;
use common\models\Linhasreserva;
use common\models\Reserva;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
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

            // Adicione a lógica para confirmar a reserva aqui
            $confirmacao->estado = 'Confirmado';
            $confirmacao->dataconfirmacao = date('Y-m-d'); // Adiciona a data de confirmação

            if ($confirmacao->save()) {
                return ['status' => 'success', 'message' => 'Reserva confirmada com sucesso.'];
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

            // Atualizar a lógica para cancelar a reserva
            $confirmacao->estado = 'Cancelado';
            $confirmacao->dataconfirmacao = date('Y-m-d'); // Adiciona apenas a data atual

            if ($confirmacao->save()) {
                return ['status' => 'success', 'message' => 'Reserva cancelada com sucesso.'];
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

        return [
            'reserva' => $reserva->attributes,
            'linha_reserva' => $linhaReserva ? $linhaReserva->attributes : null,
            'confirmacao' => $confirmacao ? $confirmacao->attributes : null,
        ];
    }

    public function FazPublishNoMosquitto($canal,$msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; // set your username
        $password = ""; // set your password
        $client_id = "phpMQTT-publisher"; // unique!
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password))
        {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        }
        else { file_put_contents("debug.output","Time out!"); }
    }
}
