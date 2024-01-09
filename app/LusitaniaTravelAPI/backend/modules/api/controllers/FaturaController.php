<?php

namespace backend\modules\api\controllers;

use backend\libs\phpMQTT\phpMQTT;
use common\models\Confirmacao;
use common\models\Fatura;
use common\models\Linhasfatura;
use common\models\Linhasreserva;
use common\models\Reserva;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
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
