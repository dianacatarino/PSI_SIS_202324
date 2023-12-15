<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class FaturaController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Fatura';

    public function actionGerarFatura($clienteId, $reservaId)
    {
        // Lógica para gerar fatura com base nos itens do carrinho
        // Certifique-se de validar os parâmetros e manipular os dados de acordo

        // Exemplo de resposta
        return ['success' => true, 'message' => 'Fatura gerada com sucesso.'];
    }

    public function actionListarFaturas($clienteId)
    {
        $query = Fatura::find()->where(['cliente_id' => $clienteId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }

    public function actionDetalhesFatura($faturaId)
    {
        $fatura = Fatura::findOne($faturaId);

        if (!$fatura) {
            throw new BadRequestHttpException('Fatura não encontrada.');
        }

        return $fatura;
    }

}
