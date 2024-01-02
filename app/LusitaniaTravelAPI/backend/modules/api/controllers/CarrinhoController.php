<?php

namespace backend\modules\api\controllers;

use frontend\models\Carrinho;
use common\models\Fornecedor;
use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class CarrinhoController extends ActiveController
{
    public $user = null;
    public $modelClass = 'frontend\models\Carrinho';

    public function actionCalculartotal($nomecliente)
    {
        // Obtém todos os itens no carrinho para o usuário com o nome específico
        $itensCarrinho = Carrinho::find()
            ->joinWith(['cliente.profile']) // Faz a junção com a tabela de perfil do usuário
            ->where(['profile.name' => $nomecliente])
            ->all();

        $totalCalculado = 0;

        // Calcula o total somando os preços de cada item
        foreach ($itensCarrinho as $item) {
            $totalCalculado += $item->subtotal;
        }

        return ['total' => $totalCalculado];
    }

    public function actionAdicionarcarrinho($fornecedorid)
    {
        // Verifica se o fornecedor existe
        $fornecedor = Fornecedor::findOne($fornecedorid);

        if (!$fornecedor) {
            throw new NotFoundHttpException('Fornecedor não encontrado.');
        }

        // Cria um novo item de carrinho
        $itemCarrinho = new Carrinho();
        $itemCarrinho->fornecedor_id = $fornecedor->id;
        // Outros campos do item do carrinho, se necessário

        // Salva o item do carrinho
        if (!$itemCarrinho->save()) {
            throw new ServerErrorHttpException('Não foi possível adicionar o item ao carrinho.');
        }

        return ['message' => 'Item adicionado ao carrinho com sucesso.'];
    }

}
