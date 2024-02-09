<?php

namespace backend\modules\api\controllers;

use backend\libs\phpMQTT\phpMQTT;
use backend\models\Empresa;
use common\models\Confirmacao;
use common\models\Fatura;
use common\models\Linhasfatura;
use common\models\Linhasreserva;
use common\models\Reserva;
use common\models\User;
use Mpdf\Mpdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class FaturaController extends ActiveController
{
    public $user = null;
    public $modelClass = 'common\models\Fatura';

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
            $allowedRoles = ['admin', 'funcionario', 'fornecedor'];

            // Verifica se o usuário tem permissão para a ação específica
            if (in_array($userRole, $allowedRoles)) {
                // O usuário tem permissão para todas as ações
                return;
            } elseif ($userRole === 'cliente' && in_array($action, ['create', 'update', 'delete'])) {
                // Usuários com papel 'cliente' não têm permissão para criar, atualizar e excluir
                throw new \yii\web\ForbiddenHttpException('Acesso negado para ação ' . $action);
            }
            // Permite ações de leitura (GET) para todos os usuários, incluindo clientes
        } else {
            // Lança uma exceção se o usuário não estiver autenticado
            throw new \yii\web\ForbiddenHttpException('Usuário não autenticado');
        }

        // Obtém o utilizador autenticado
        //$authenticatedUser = $this->user; // Certifique-se de que $this->user foi definido durante a autenticação
    }

    public function actionGerarfatura($nomecliente, $reserva_id)
    {
        // Encontrar o cliente pelo nome do perfil
        $cliente = User::find()
            ->joinWith('profile')
            ->where(['profile.name' => $nomecliente])
            ->one();

        if ($cliente !== null) {
            // Encontrar a reserva pelo ID fornecido
            $reserva = Reserva::findOne($reserva_id);

            if ($reserva !== null) {
                // Encontrar a confirmação associada à reserva
                $confirmacao = Confirmacao::findOne(['reserva_id' => $reserva_id]);

                if ($confirmacao !== null) {
                    // Verificar o estado da reserva associada à confirmação
                    if ($confirmacao->estado == 'Confirmado') {
                        // Verificar se a reserva pertence ao cliente especificado
                        if ($reserva->cliente_id == $cliente->id) {
                            $fatura = new Fatura();

                            // Preencher os campos da fatura com base na lógica específica do seu sistema
                            $fatura->totalf = $reserva->valor;
                            $fatura->totalsi = $reserva->valor - 0.23;
                            $fatura->iva = 0.23;
                            $fatura->empresa_id = 1;
                            $fatura->reserva_id = $reserva_id;
                            $fatura->data = date('Y-m-d');

                            // Salvar a fatura no banco de dados
                            if ($fatura->save()) {
                                // Criar Linhafatura a partir de Linhasreservas
                                $linhasReservas = Linhasreserva::findAll(['reservas_id' => $reserva_id]);

                                foreach ($linhasReservas as $linhaReserva) {
                                    $linhaFatura = new Linhasfatura();
                                    $linhaFatura->quantidade = 1;
                                    $linhaFatura->precounitario = $linhaReserva->subtotal;
                                    $linhaFatura->subtotal = $reserva->valor;
                                    $linhaFatura->iva = 0.23;
                                    $linhaFatura->fatura_id = $fatura->id;
                                    $linhaFatura->linhasreservas_id = $linhaReserva->id;

                                    // Salvar a Linhafatura no banco de dados
                                    $linhaFatura->save();
                                }

                                // Obter todas as linhas de fatura associadas à fatura
                                $linhasFatura = Linhasfatura::findAll(['fatura_id' => $fatura->id]);

                                $dadosFatura = [
                                    'fatura' => $fatura->attributes,
                                    'linhasFatura' => [],
                                ];

                                // Adicionar os atributos das linhas de fatura
                                foreach ($linhasFatura as $linhaFatura) {
                                    $dadosFatura['linhasFatura'][] = $linhaFatura->attributes;
                                }

                                // Adicionar a publicação no Mosquitto
                                $canal = 'fatura'; // Substitua pelo canal desejado
                                $msg = 'Fatura gerada para reserva ' . $reserva_id;
                                $this->FazPublishNoMosquitto($canal, $msg);

                                return ['success' => true, 'message' => 'Fatura gerada com sucesso.', 'dadosFatura' => $dadosFatura];
                            } else {
                                throw new ServerErrorHttpException('Erro ao salvar a fatura.');
                            }
                        } else {
                            throw new \yii\web\ForbiddenHttpException('A reserva não pertence ao cliente especificado.');
                        }
                    } else {
                        throw new \yii\web\ForbiddenHttpException('A reserva associada à confirmação não está confirmada.');
                    }
                } else {
                    throw new \yii\web\NotFoundHttpException('Confirmação não encontrada para a reserva.');
                }
            } else {
                throw new \yii\web\NotFoundHttpException('Reserva não encontrada.');
            }
        } else {
            throw new \yii\web\NotFoundHttpException('Cliente não encontrado.');
        }
    }

    public function actionVerfatura($username)
    {
        // Obter o usuário logado
        $currentUser = Yii::$app->user->identity;

        // Verificar se o usuário logado tem permissão para acessar as faturas
        if ($currentUser->username !== $username) {
            throw new \yii\web\ForbiddenHttpException('Access denied.');
        }

        // Encontrar todas as reservas associadas ao cliente
        $reservas = Reserva::find()
            ->joinWith(['cliente.profile']) // Faz a junção com a tabela de perfil do usuário
            ->where(['profile.name' => $username])
            ->all();

        $dadosFaturas = [];

        // Verificar se existem reservas
        if (!empty($reservas)) {
            foreach ($reservas as $reserva) {
                // Verificar se há faturas associadas à reserva
                $faturas = Fatura::find()
                    ->where(['reserva_id' => $reserva->id])
                    ->all();

                foreach ($faturas as $fatura) {
                    // Adicionar os atributos da fatura
                    $dadosFatura = [
                        'id' => $fatura->id,
                        'totalf' => $fatura->totalf,
                        'totalsi' => $fatura->totalsi,
                        'iva' => $fatura->iva,
                        'empresa_id' => $fatura->empresa_id,
                        'reserva_id' => $fatura->reserva_id,
                        'data' => $fatura->data,
                    ];

                    $dadosFaturas[] = $dadosFatura;
                }
            }
        } else {
            $mensagemFatura = 'Nenhuma fatura disponível no momento';
        }

        return [
            'message' => isset($mensagemFatura) ? $mensagemFatura : 'Mostrando faturas para o cliente ' . $username,
            'faturas' => $dadosFaturas,
        ];
    }

    public function actionMostrarfatura($nomecliente)
    {
        // Encontrar todas as reservas associadas ao cliente
        $reservas = Reserva::find()
            ->joinWith(['cliente.profile']) // Faz a junção com a tabela de perfil do usuário
            ->where(['profile.name' => $nomecliente])
            ->all();

        $dadosReservas = [];

        foreach ($reservas as $reserva) {
            // Verificar se há faturas associadas à reserva
            $faturas = Fatura::find()
                ->where(['reserva_id' => $reserva->id])
                ->all();

            if (!empty($faturas)) {
                $mensagemReserva = 'Mostrando faturas para a reserva ' . $reserva->id;
                $dadosFaturas = [];

                foreach ($faturas as $fatura) {
                    // Obter as linhas de fatura associadas à fatura
                    $linhasFatura = Linhasfatura::find()->where(['fatura_id' => $fatura->id])->all();

                    // Adicionar os atributos da fatura
                    $dadosFatura = [
                        'id' => $fatura->id,
                        'totalf' => $fatura->totalf,
                        'totalsi' => $fatura->totalsi,
                        'iva' => $fatura->iva,
                        'empresa_id' => $fatura->empresa_id,
                        'reserva_id' => $fatura->reserva_id,
                        'data' => $fatura->data,
                    ];

                    // Adicionar os atributos das linhas de fatura
                    $dadosFatura['linhasFatura'] = [];

                    foreach ($linhasFatura as $linhaFatura) {
                        $dadosFatura['linhasFatura'][] = [
                            'id' => $linhaFatura->id,
                            'quantidade' => $linhaFatura->quantidade,
                            'precounitario' => $linhaFatura->precounitario,
                            'subtotal' => $linhaFatura->subtotal,
                            'fatura_id' => $linhaFatura->fatura_id,
                            'linhasreservas_id' => $linhaFatura->linhasreservas_id,
                        ];
                    }

                    $dadosFaturas[] = $dadosFatura;
                }

                $dadosReserva = [
                    'message' => $mensagemReserva,
                    'faturas' => $dadosFaturas,
                ];

                $dadosReservas[] = $dadosReserva;
            }
        }

        return [
            'message' => 'Mostrando reservas com faturas para o cliente ' . $nomecliente,
            'reservas' => $dadosReservas,
        ];
    }

    public function actionDetalhesfatura($id)
    {
        // Encontrar a fatura pelo ID fornecido
        $fatura = Fatura::findOne($id);

        if ($fatura !== null) {
            // Encontrar as linhas de fatura associadas à fatura
            $linhasFatura = LinhasFatura::find()->where(['fatura_id' => $fatura->id])->all();

            // Retornar os atributos da fatura e das linhas de fatura
            $atributosFatura = $fatura->attributes;
            $atributosLinhasFatura = [];

            foreach ($linhasFatura as $linhaFatura) {
                $atributosLinhasFatura[] = $linhaFatura->attributes;
            }

            return [
                'fatura' => $atributosFatura,
                'linhasFatura' => $atributosLinhasFatura,
            ];
        } else {
            throw new \yii\web\NotFoundHttpException('Fatura não encontrada.');
        }
    }

    public function actionDownload($id)
    {
        $fatura = Fatura::findOne($id);

        if ($fatura === null) {
            throw new NotFoundHttpException('Fatura não encontrada.');
        }

        $fileContent = $this->gerarFaturaPdf($fatura);

        // Configurar o cabeçalho da resposta para o download
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/pdf');
        Yii::$app->response->headers->add('Content-Disposition', 'attachment; filename="fatura_' . $fatura->id . '.pdf"');

        // Enviar o conteúdo do arquivo para o navegador
        Yii::$app->response->content = $fileContent;

        return Yii::$app->response;
    }

    private function gerarFaturaPdf($fatura)
    {
        // Configuração mPDF
        $mpdf = new Mpdf();

        // Conteúdo do PDF
        $pdfContent = '<div>';
        $pdfContent .= '<div style="float: left; margin-bottom: 20px;">';
        $pdfContent .= '<img src="/LusitaniaTravel/frontend/public/img/logo_icon.png" alt="Imagem da Empresa" style="width: 100px; height: auto; margin-right: 20px;">';
        $pdfContent .= '<h1>Fatura ' . $fatura->id . '</h1>';
        $pdfContent .= '</div>';
        $pdfContent .= '<div style="clear: both;"></div>'; // Limpar flutuações
        $pdfContent .= '<div style="float: right; margin-top: 10px;">' . $fatura->data . '</div>';
        $pdfContent .= '</div>';

        // Adiciona detalhes da empresa (lado esquerdo)
        $empresa = Empresa::findOne($fatura->empresa_id);
        if ($empresa !== null) {
            $pdfContent .= '<div style="float: left; width: 50%; text-align: left;">';
            $enderecoEmpresa = $empresa->morada . ', ' . $empresa->localidade;
            $pdfContent .= '<p>' . $empresa->sede . '</p>';
            $pdfContent .= '<p>' . $enderecoEmpresa . '</p>';
            $pdfContent .= '<p>' . $empresa->email . '</p>';
            $pdfContent .= '<p>' . $empresa->nif . '</p>';
            $pdfContent .= '</div>';
        }

        // Adiciona detalhes do cliente (lado direito)
        $reserva = Reserva::findOne($fatura->reserva_id);
        $pdfContent .= '<div style="float: right; width: 50%; text-align: right;">';
        $enderecoCliente = $reserva->cliente->profile->street . ', ' . $reserva->cliente->profile->locale;
        $pdfContent .= '<p>' . $reserva->cliente->profile->name . '</p>';
        $pdfContent .= '<p>' . $enderecoCliente . '</p>';
        $pdfContent .= '<p>' . $reserva->cliente->profile->postalCode . '</p>';
        $pdfContent .= '<p>' . $reserva->cliente->email . '</p>';
        $pdfContent .= '<p>' . $reserva->cliente->profile->mobile . '</p>';
        $pdfContent .= '</div>';

        $pdfContent .= '<div style="clear: both;"></div>'; // Limpar flutuações
        $pdfContent .= '</div>'; // Fechar a div principal

        // Adiciona detalhes das linhas de fatura
        $pdfContent .= '<div style="margin-top: 20px;">';
        $pdfContent .= '<table border="1" style="width: 100%;">';
        $pdfContent .= '<tr>';
        $pdfContent .= '<th>Quantidade</th>';
        $pdfContent .= '<th>Preço Unitário</th>';
        $pdfContent .= '<th>Subtotal</th>';
        $pdfContent .= '</tr>';

        // Obtenha as linhas de fatura associadas a esta fatura
        $linhasFatura = LinhasFatura::findAll(['fatura_id' => $fatura->id]);
        foreach ($linhasFatura as $linha) {
            $pdfContent .= '<tr>';
            $pdfContent .= '<td>' . $linha->quantidade . '</td>';
            $pdfContent .= '<td>' . $linha->precounitario . '€</td>';
            $pdfContent .= '<td>' . $linha->subtotal . '€</td>';
            $pdfContent .= '</tr>';
        }

        $pdfContent .= '</table>';
        $pdfContent .= '</div>';

        // Adiciona detalhes da fatura
        $pdfContent .= '<div style="clear: both; margin-top: 20px;">';
        $pdfContent .= '<p><strong>Total: </strong>' . $fatura->totalf . '€</p>';
        $pdfContent .= '<p><strong>Total sem IVA: </strong>' . $fatura->totalsi . '€</p>';
        $pdfContent .= '<p><strong>IVA: </strong>' . $fatura->iva * 100 . '%</p>';
        // Adicione outros campos da tabela Faturas conforme necessário
        $pdfContent .= '</div>';

        // Adiciona o nome do funcionário
        $pdfContent .= '<div style="margin-top: 20px;">';
        $pdfContent .= '<p><strong>Funcionário: </strong>' . $reserva->funcionario->profile->name . '</p>';
        $pdfContent .= '</div>';

        $pdfContent .= '<div style="clear: both;"></div>'; // Limpar flutuações
        $pdfContent .= '</div>'; // Fechar a div principal

        // Adicionar conteúdo ao mPDF
        $mpdf->WriteHTML($pdfContent);

        // Saída para uma variável
        $fileContent = $mpdf->Output('', 'S');

        return $fileContent;
    }


    public function FazPublishNoMosquitto($canal,$msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; // set your username
        $password = ""; // set your password
        $client_id = "phpMQTT-publisher"; // unique!
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password))
        {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        }
        else { file_put_contents("debug.output","Time out!"); }
    }
}