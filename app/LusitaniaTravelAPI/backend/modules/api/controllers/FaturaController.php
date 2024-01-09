<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Fatura;
use common\models\Reserva;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;

class FaturaController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Fatura';

    public function actionGerarFatura($cliente_id, $reserva_id, $id){

        // Encontrar a reserva pelo ID fornecido
        $reserva = Reserva::findOne($reserva_id);

        if ($reserva !== null) {
            $fatura = new Fatura();

            $fatura->totalf = $reserva->calcularTotalFatura();
            $fatura->totalsi = $reserva->calcularTotalSI();
            $fatura->iva = $fatura->totalf * 0.23;
            $fatura->empresa_id = $reserva->empresa_id;
            $fatura->reserva_id = $reserva_id;

            if ($fatura->save()) {

                // Inicializa o cliente MQTT
                $mqtt = new phpMQTT("127.0.0.1", 1883, "cliente_id");

                if ($mqtt->connect()) {
                    $mqtt->publish("topico/fatura_gerada", "Fatura confirmada com ID: " . $id);
                    $mqtt->close();
                }
                return ['success' => true, 'message' => 'Fatura gerada com sucesso.'];
            } else {
                throw new ServerErrorHttpException('Erro ao salvar a confirmação.');
            }
        }
    }

    public function actionListarFaturas($cliente_id){

        $query = Fatura::find()->where(['cliente_id' => $cliente_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('listar', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetalhesFatura($id)
    {
        $fatura = Fatura::findOne($id); // Buscar a fatura pelo ID fornecido

        if ($fatura !== null) {
            return $this->render('detalhes', [
                'fatura' => $fatura,
            ]);
        } else {
            throw new \yii\web\NotFoundHttpException('Fatura não encontrada.');
        }
    }



}
