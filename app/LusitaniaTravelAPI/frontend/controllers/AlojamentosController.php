<?php

namespace frontend\controllers;

use common\models\Fornecedor;
use common\models\Comentario;

class AlojamentosController extends \yii\web\Controller
{

    public function actionListarFornecedoresHoteis()
    {
        $query = Fornecedor::find()->where(['tipo' => 'Hotel']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }

    public function actionListarAlojamentosLisboa()
    {
        $query = Fornecedor::find()->where(['localizacao' => 'Lisboa']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }

    public function actionComentariosFornecedorData($id = 1, $data = '2023-12-12')
    {
        $query = Comentario::find()->where(['fornecedor_id' => $id, 'data' => $data]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }

    public function actionListarAvaliacoesFornecedor($id = 1)
    {
        $query = Avaliacao::find()->where(['fornecedor_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }
    

}
