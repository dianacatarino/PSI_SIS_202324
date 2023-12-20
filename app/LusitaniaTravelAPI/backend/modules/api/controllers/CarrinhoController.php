<?php

namespace backend\modules\api\controllers;

use common\models\Carrinho;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class CarrinhoController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Carrinho';

    public function actionAdicionarItem($clienteId, $fornecedorId, $reservaId, $quantidade, $preco)
    {
        try {
            // Valide os parâmetros, manipule os dados e adicione o item ao carrinho
            // Exemplo básico, você deve ajustar de acordo com sua lógica
            $carrinho = new Carrinho();
            $carrinho->cliente_id = $clienteId;
            $carrinho->fornecedor_id = $fornecedorId;
            $carrinho->reserva_id = $reservaId;
            $carrinho->quantidade = $quantidade;
            $carrinho->preco = $preco;

            if ($carrinho->save()) {
                return ['success' => true, 'message' => 'Item adicionado ao carrinho com sucesso.'];
            } else {
                throw new ServerErrorHttpException('Erro ao salvar o item no carrinho.');
            }
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Erro interno do servidor.', 500, $e);
        }
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
        try {
            // Valide o parâmetro, encontre o item e remova do carrinho
            $carrinho = Carrinho::findOne($itemId);

            if (!$carrinho) {
                throw new NotFoundHttpException("Item do carrinho com ID $itemId não encontrado.");
            }

            if ($carrinho->delete()) {
                return ['success' => true, 'message' => 'Item removido do carrinho com sucesso.'];
            } else {
                throw new ServerErrorHttpException('Erro ao remover o item do carrinho.');
            }
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Erro interno do servidor.', 500, $e);
        }
    }
}
