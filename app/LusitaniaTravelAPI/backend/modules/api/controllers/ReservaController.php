<?php

namespace backend\modules\api\controllers;

use common\models\Confirmacao;
use yii\rest\ActiveController;

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

    public function actionReservasconfirmadas()
    {
        $reservaModel = new $this->modelClass;
        $confirmacoes = Confirmacao::find()->where(['estado' => 'Confirmado'])->all();

        $reservasConfirmadas = [];
        foreach ($confirmacoes as $confirmacao) {
            $reserva = $reservaModel::findOne($confirmacao->reserva_id);
            if ($reserva) {
                $reservasConfirmadas[] = $reserva;
            }
        }

        return $reservasConfirmadas;
    }


}
