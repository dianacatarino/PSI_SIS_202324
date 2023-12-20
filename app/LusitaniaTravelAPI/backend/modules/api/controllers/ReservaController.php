<?php

namespace backend\modules\api\controllers;

use common\models\Confirmacao;
use common\models\Reserva;
use common\models\User;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ReservaController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Reserva';

    public function actionCount()
    {
        $reservamodel = new $this->modelClass;
        $recs = $reservamodel::find()->all();
        return ['count' => count($recs)];
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

        return ['taxa_reservas' => $taxaReservasConfirmadas];
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


}
