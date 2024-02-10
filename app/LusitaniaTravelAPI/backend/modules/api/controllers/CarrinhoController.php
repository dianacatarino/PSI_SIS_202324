<?php

namespace backend\modules\api\controllers;

use common\models\Confirmacao;
use common\models\Fatura;
use common\models\Linhasfatura;
use common\models\Linhasreserva;
use common\models\Reserva;
use common\models\User;
use frontend\models\Carrinho;
use common\models\Fornecedor;
use Mpdf\Mpdf;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class CarrinhoController extends ActiveController
{
    public $user = null;
    public $modelClass = 'frontend\models\Carrinho';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(), // ou QueryParamAuth::className(),
            //’except' => ['index', 'view'], //Excluir aos GETs
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }

    public function auth($username, $password)
    {
        $user = \common\models\User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            $this->user=$user; //Guardar user autenticado
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('No authentication'); //403
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        // Certifique-se de que $this->user foi definido durante a autenticação
        if ($this->user && Yii::$app->user->identity) {
            // Obtém o papel do perfil do usuário
            $userRole = Yii::$app->user->identity->profile->role;

            // Define os papéis permitidos para acessar ações de criar, atualizar e excluir
            $allowedRoles = ['admin', 'funcionario', 'fornecedor', 'cliente'];

            // Verifica se o usuário tem permissão para a ação específica
            if (in_array($userRole, $allowedRoles)) {
                // Negar acesso a ações customizadas para admin, funcionario e fornecedor
                if (in_array($action, ['Adicionarcarrinho', 'Removercarrinho','Finalizarcarrinho'])) {
                    if (in_array($userRole, ['admin', 'funcionario', 'fornecedor'])) {
                        throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);
                    }
                }
                // O user tem permissão para todas as ações
                return;
            } elseif ($userRole === 'cliente' && in_array($action, ['create', 'update', 'delete'])) {
                // Users com papel 'cliente' têm permissão para criar, atualizar e excluir
                return;
            }
        }

        // Lança uma exceção se o usuário não estiver autenticado ou não tiver permissão
        throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);


        // Obtém o utilizador autenticado
        //$authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação
    }

    public function actionCalculartotal($nomecliente)
    {
        // Obtém todos os itens no carrinho para o usuário com o nome específico
        $itensCarrinho = Carrinho::find()
            ->joinWith(['cliente.profile']) // Faz a junção com a tabela de perfil do usuário
            ->where(['profile.name' => $nomecliente])
            ->all();

        $totalCalculado = 0;
        $carrinhoDetalhes = [];

        // Calcula o total somando os preços de cada item e armazena detalhes do carrinho
        foreach ($itensCarrinho as $item) {
            $totalCalculado += $item->subtotal;

            // Armazena detalhes do carrinho
            $carrinhoDetalhes[] = [
                'id' => $item->id,
                'fornecedor' => $item->fornecedor->nome_alojamento,
                'cliente_id' => $item->cliente_id,
                'quantidade' => $item->quantidade,
                'preco' => $item->preco,
                'subtotal' => $item->subtotal,
                'reserva_id' => $item->reserva_id,
            ];
        }

        return [
            'total' => $totalCalculado,
            'carrinhoDetalhes' => $carrinhoDetalhes,
        ];
    }

    public function actionAdicionarcarrinho($fornecedorId)
    {
        $fornecedor = Fornecedor::findOne($fornecedorId);

        if ($fornecedor === null) {
            throw new NotFoundHttpException('O fornecedor não foi encontrado.');
        }

        $clienteId = Yii::$app->user->identity->id;

        $funcionarioId = 45;

        // Cria uma nova reserva associada ao carrinho
        $reserva = new Reserva([
            'tipo' => 'Online',
            'checkin' => '0000-00-00',
            'checkout' => '0000-00-00',
            'numeroquartos' => 0,
            'numeroclientes' => 0,
            'valor' => 0,
            'cliente_id' => $clienteId,
            'fornecedor_id' => $fornecedorId,
            'funcionario_id' => $funcionarioId,
        ]);

        // Salva a reserva
        $reserva->save();

        // Obtém o ID da reserva recém-criada
        $reservaId = $reserva->id;

        // Cria uma nova confirmação associada à reserva com o estado "pendente"
        $confirmacao = new Confirmacao([
            'reserva_id' => $reservaId,
            'estado' => 'Pendente',
            'dataconfirmacao' => '0000-00-00',
            'fornecedor_id' => $fornecedorId,
        ]);

        // Salva a confirmação
        $confirmacao->save();

        $subtotal = 1 * $fornecedor->precopornoite;

        // Cria um novo item no carrinho associado à reserva
        $carrinhoExistente = new Carrinho([
            'fornecedor_id' => $fornecedorId,
            'cliente_id' => $clienteId,
            'quantidade' => 1,
            'preco' => $fornecedor->precopornoite,
            'subtotal' => $subtotal,
            'reserva_id' => $reservaId,
        ]);

        // Salva o item no carrinho
        $carrinhoExistente->save();

        // Obtém o nome do alojamento e o nome do cliente
        $nomeAlojamento = $fornecedor->nome_alojamento;
        $nomeCliente = $carrinhoExistente->cliente->profile->name;

        // Retorna uma resposta adequada ao cliente
        return [
            'message' => 'Item adicionado ao carrinho com sucesso.',
            'data' => [
                'nome_alojamento' => $nomeAlojamento,
                'cliente_nome' => $nomeCliente,
                'quantidade' => $carrinhoExistente->quantidade,
                'preco' => $carrinhoExistente->preco,
                'subtotal' => $carrinhoExistente->subtotal,
                'reserva_id' => $carrinhoExistente->reserva_id,
            ],
        ];
    }


    public function actionRemovercarrinho($fornecedorId)
    {
        // Obtém o ID do cliente atualmente autenticado
        $clienteId = Yii::$app->user->identity->id;

        // Encontra todos os itens no carrinho para o cliente e fornecedor específicos
        $itensCarrinho = Carrinho::find()
            ->where(['cliente_id' => $clienteId, 'fornecedor_id' => $fornecedorId])
            ->all();

        // Inicializa um array para armazenar os atributos do carrinho removido
        $carrinhoRemovido = [];

        foreach ($itensCarrinho as $item) {
            // Armazena os atributos do carrinho antes de removê-lo
            $carrinhoRemovido[] = [
                'nome_alojamento' => $item->fornecedor->nome_alojamento,
                'cliente_nome' => $item->cliente->profile->name,
                'quantidade' => $item->quantidade,
                'preco' => $item->preco,
                'subtotal' => $item->subtotal,
                'reserva_id' => $item->reserva_id,
            ];

            // Remove o item do carrinho
            $item->delete();

            // Verifica se existe uma confirmação associada à reserva
            $confirmacao = Confirmacao::findOne(['reserva_id' => $item->reserva_id]);
            if ($confirmacao !== null) {
                // Remove a confirmação
                $confirmacao->delete();
            }

            // Exclui a reserva associada ao item
            Reserva::deleteAll(['id' => $item->reserva_id]);
        }

        // Retorna uma resposta que inclui os atributos do carrinho removido
        return [
            'message' => 'Itens removidos do carrinho com sucesso para o fornecedor ID ' . $fornecedorId,
            'carrinho_removido' => $carrinhoRemovido,
        ];
    }


    public function actionFinalizarcarrinho($reservaId)
    {
        // Obtém o ID do cliente atualmente autenticado
        $clienteId = Yii::$app->user->identity->id;

        // Encontra todos os itens no carrinho para a reserva específica
        $itensCarrinho = Carrinho::find()
            ->where(['cliente_id' => $clienteId, 'reserva_id' => $reservaId])
            ->all();

        // Verifica se a reserva existe
        $reserva = Reserva::findOne($reservaId);
        if (!$reserva) {
            throw new NotFoundHttpException('Reserva não encontrada.');
        }

        // Lógica para limpar os itens do carrinho associados à reserva
        foreach ($itensCarrinho as $item) {
            $item->delete();
        }

        // Lógica para criar a fatura e as linhas de fatura
        $fatura = new Fatura();
        $fatura->totalf = $reserva->valor;
        $fatura->totalsi = $reserva->valor - 0.23;
        $fatura->iva = 0.23;
        $fatura->empresa_id = 1;
        $fatura->reserva_id = $reserva->id;
        $fatura->data = date('Y-m-d');
        $fatura->save();

        // Buscar as LinhasReservas associadas à reserva
        $linhasReservas = Linhasreserva::findAll(['reservas_id' => $reserva->id]);

        foreach ($itensCarrinho as $item) {
            foreach ($linhasReservas as $linhaReserva) {
                $linhaFatura = new LinhasFatura();
                $linhaFatura->quantidade = count($linhasReservas);
                $linhaFatura->precounitario = $item->preco;
                $linhaFatura->subtotal = $item->subtotal;
                $linhaFatura->iva = 0.23;
                $linhaFatura->fatura_id = $fatura->id;
                $linhaFatura->linhasreservas_id = $linhaReserva->id;
                $linhaFatura->save();
            }
        }

        // Mensagem de sucesso e atributos da reserva
        $mensagem = 'Reserva finalizada com sucesso.';
        $atributosReserva = $reserva->attributes;

        return [
            'message' => $mensagem,
            'reserva' => $atributosReserva,
        ];
    }

    public function actionMostrarcarrinho()
    {
        // Verifica se o usuário está logado
        if (!Yii::$app->user->isGuest) {
            // Obtém o ID do cliente atualmente autenticado
            $clienteId = Yii::$app->user->identity->id;

            // Obtém todos os itens no carrinho para o cliente específico
            $itensCarrinho = Carrinho::find()
                ->where(['cliente_id' => $clienteId])
                ->all();

            // Verifica se o carrinho está vazio
            if (empty($itensCarrinho)) {
                return ['message' => 'O carrinho está vazio'];
            }

            // Inicializa um array para armazenar os detalhes do carrinho
            $detalhesCarrinho = [];

            // Obtém os detalhes de cada item no carrinho
            foreach ($itensCarrinho as $item) {
                $detalhesCarrinho[] = [
                    'id' => $item->id,
                    'fornecedor' => $item->fornecedor->nome_alojamento,
                    'quantidade' => $item->quantidade,
                    'preco' => $item->preco,
                    'subtotal' => $item->subtotal,
                    'reserva_id' => $item->reserva_id,
                    'estado' => $item->reserva->confirmacao->estado ?? 'Pendente',
                    'cliente' => $item->cliente->profile->name,
                ];
            }

            // Retorna os detalhes do carrinho
            return ['carrinho' => $detalhesCarrinho];
        } else {
            throw new \yii\web\ForbiddenHttpException('User não autenticado.');
        }
    }

    public function actionDownloadpagamento($reservaId)
    {
        // Buscar a reserva pelo ID fornecido
        $reserva = Reserva::findOne($reservaId);

        // Verificar se a reserva foi encontrada
        if ($reserva === null) {
            throw new NotFoundHttpException('Reserva não encontrada.');
        }

        // Configurar a instância do mPDF
        $mpdf = new Mpdf();

        // Adicionar conteúdo ao PDF
        $content = "
        <div style='text-align: center;'>
        <img src='/LusitaniaTravel/frontend/public/img/logo_vertical.png' alt='Logo' style='width: 200px; height: 200px;'>
        <p>Entidade: 21223</p>
        <p>Referência: REF" . str_pad($reserva->id, 8, '0', STR_PAD_LEFT) . "</p>
        <p>Valor: " . Yii::$app->formatter->asCurrency($reserva->valor, 'EUR') . "</p>
        </div>";

        $mpdf->WriteHTML($content);

        // Definir o nome do arquivo
        $filename = 'pagamento_' . $reserva->id . '.pdf';

        // Configurar a resposta HTTP para download
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/pdf');
        Yii::$app->response->headers->add('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Saída do conteúdo PDF para o navegador
        Yii::$app->response->content = $mpdf->Output('', 'S');

        // Enviar a resposta e encerrar a execução
        Yii::$app->response->send();
        Yii::$app->end();
    }
}