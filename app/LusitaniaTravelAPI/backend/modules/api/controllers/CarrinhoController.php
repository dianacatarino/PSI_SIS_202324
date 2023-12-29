<?php

namespace backend\modules\api\controllers;

use frontend\models\Carrinho;
use common\models\Fornecedor;
use Yii;
use yii\rest\ActiveController;
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

    public function actionAdicionarcarrinho($fornecedorId)
    {
        $request = Yii::$app->getRequest();

        // Certifique-se de que você tem os parâmetros necessários
        $quantidade = $request->getBodyParam('quantidade');

        // Valide os parâmetros conforme necessário
        if ($quantidade === null || !is_numeric($quantidade) || $quantidade <= 0) {
            throw new ServerErrorHttpException('Parâmetros inválidos para adicionar ao carrinho.');
        }

        // Encontre o fornecedor com base no ID
        $fornecedor = Fornecedor::findOne($fornecedorId);

        // Verifique se o fornecedor existe
        if ($fornecedor === null) {
            throw new ServerErrorHttpException('Fornecedor não encontrado.');
        }

        // Crie uma nova instância do modelo Carrinho
        $carrinhoItem = new Carrinho([
            'fornecedor_id' => $fornecedorId,
            'quantidade' => $quantidade,
            'preco' => $fornecedor->precopornoite, // ajuste conforme necessário
            // outros campos do carrinho, se necessário
        ]);

        // Salve o item do carrinho
        if (!$carrinhoItem->save()) {
            throw new ServerErrorHttpException('Não foi possível adicionar o item ao carrinho.');
        }

        return ['message' => 'Item adicionado ao carrinho com sucesso.'];
    }
}
