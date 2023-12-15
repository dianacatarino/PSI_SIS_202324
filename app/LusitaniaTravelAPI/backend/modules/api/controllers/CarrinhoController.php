<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class CarrinhoController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Carrinho';

    public function actionAdicionarItem($clienteId, $fornecedorId, $reservaId, $quantidade, $preco)
    {
        // Lógica para adicionar item ao carrinho
        // Certifique-se de validar os parâmetros e manipular os dados de acordo

        // Exemplo de resposta
        return ['success' => true, 'message' => 'Item adicionado ao carrinho com sucesso.'];
    }

    public function actionListarItens($clienteId)
    {
        $query = Carrinho::find()->where(['cliente_id' => $clienteId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider->getModels();
    }

    public function actionRemoverItem($itemId)
    {
        // Lógica para remover item do carrinho
        // Certifique-se de validar o parâmetro e manipular os dados de acordo

        // Exemplo de resposta
        return ['success' => true, 'message' => 'Item removido do carrinho com sucesso.'];
    }
}
