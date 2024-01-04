<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Carrinho;
use common\models\User;
use common\models\Fornecedor;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;


class CarrinhoController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Carrinho';

    public function actionTotalCarrinhoPorCliente($nomeCliente = 'user')
    {
        $cliente = User::find()->where(['nome' => $nomeCliente])->one();

        if (!$cliente) {
            return ['error' => 'Cliente não encontrado.'];
        }
        $totalCarrinho = Carrinho::find()->where(['cliente_id' => $cliente->id])->sum('total');
        return ['total_carrinho' => $totalCarrinho];
    }

    public function actionAdicionarAoCarrinho($fornecedor_id)
    {
        $fornecedor = Alojamento::findOne($fornecedor_id);

        if (!$fornecedor) {
            return ['error' => 'Alojamento não encontrado.'];
        }

        //TODO

        $novoItem = new Carrinho();
        $novoItem->alojamento_id = $fornecedor->id;
        $novoItem->preco = $fornecedor->valor;

        if ($novoItem->save()) {
            return ['success' => true, 'message' => 'Item adicionado ao carrinho com sucesso.'];
        } else {
            return ['success' => false, 'message' => 'Erro ao adicionar item ao carrinho.'];
        }


    }
}