<?php

namespace frontend\controllers;

use common\models\Reserva;

class ReservasController extends \yii\web\Controller
{
    public $user = null;
    public $modelClass = 'common\models\Reserva';

    public function actionReservasConfirmadas()
    {
        $query = Reserva::find()->where(['confirmada' => true]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }

    public function actionConfirmarReserva($reservaId)
    {
        $reserva = Reserva::findOne($reservaId);

        if (!$reserva) {
            throw new \yii\web\NotFoundHttpException('Reserva não encontrada.');
        }

        $reserva->confirmada = true; //Atualiza o estado da reserva

        if ($reserva->save()) {
            return ['success' => true, 'message' => 'Reserva confirmada com sucesso.'];
        } else {
            return ['success' => false, 'message' => 'Erro ao confirmar reserva.'];
        }
    }

    public function actionCancelarReserva($reservaId)
    {
        $reserva = Reserva::findOne($reservaId);

        if (!$reserva) {
            throw new \yii\web\NotFoundHttpException('Reserva não encontrada.');
        }

        $reserva->status = 'Cancelada';

        if ($reserva->save()) {
            return ['success' => true, 'message' => 'Reserva cancelada com sucesso.'];
        } else {
            return ['success' => false, 'message' => 'Erro ao cancelar reserva.'];
        }
    }

    public function actionTaxaDeReservas()
    {
        $query = Reserva::find();

        //Introduzir calculos da taxa

        $taxaTotal = $query->sum('valor_total'); // Supondo que 'valor_total' seja o campo que representa o valor total da reserva

        return ['taxa_total' => $taxaTotal];
    }


}
