<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Criar os roles
        $adminRole = $auth->createRole('administrador');
        $funcionarioRole = $auth->createRole('funcionario');
        $fornecedorRole = $auth->createRole('fornecedor');
        $clienteRole = $auth->createRole('cliente');

        // Adicionar os roles ao authManager
        $auth->add($adminRole);
        $auth->add($funcionarioRole);
        $auth->add($fornecedorRole);
        $auth->add($clienteRole);

        // Criar as permissões
        $criarAlojamentos = $auth->createPermission('criarAlojamentos');
        $editarAlojamentos = $auth->createPermission('editarAlojamentos');
        $detalhesAlojamentos = $auth->createPermission('verAlojamentos');
        $eliminarAlojamentos = $auth->createPermission('eliminarAlojamentos');
        $criarReservas = $auth->createPermission('criarReservas');
        $editarReservas = $auth->createPermission('editarReservas');
        $verReservas = $auth->createPermission('verReservas');
        $eliminarReservas = $auth->createPermission('eliminarReservas');
        $confirmarReserva = $auth->createPermission('confirmarReserva');
        $reservarOnline = $auth->createPermission('reservarOnline');
        $adicionarcarrinhoCompras = $auth->createPermission('adicionarcarrinhoCompras');
        $consultarFaturas = $auth->createPermission('consultarFaturas');
        $adicionarFavoritos = $auth->createPermission('adicionarFavoritos');
        $classificarecomentarAlojamentos = $auth->createPermission('classificarecomentarAlojamentos');
        $pagarReserva = $auth->createPermission('pagarReserva');
        $reservarPresencial = $auth->createPermission('reservarPresencial');
        $emitirFaturas = $auth->createPermission('emitirFaturas');
        $calcularValoresIva = $auth->createPermission('calcularValoresIva');
        $gerarRelatorios = $auth->createPermission('gerarRelatorios');
        $visualizarRelatorios = $auth->createPermission('visualizarRelatorios');
        $criarClientes = $auth->createPermission('criarClientes');
        $editarClientes = $auth->createPermission('editarClientes');
        $verClientes = $auth->createPermission('verClientes');
        $eliminarClientes = $auth->createPermission('eliminarClientes');


        // Adicionar as permissões ao authManager
        $auth->add($criarAlojamentos);
        $auth->add($editarAlojamentos);
        $auth->add($detalhesAlojamentos);
        $auth->add($eliminarAlojamentos);
        $auth->add($criarReservas);
        $auth->add($editarReservas);
        $auth->add($verReservas);
        $auth->add($eliminarReservas);
        $auth->add($confirmarReserva);
        $auth->add($reservarOnline);
        $auth->add($adicionarcarrinhoCompras);
        $auth->add($adicionarFavoritos);
        $auth->add($consultarFaturas);
        $auth->add($classificarecomentarAlojamentos);
        $auth->add($pagarReserva);
        $auth->add($reservarPresencial);
        $auth->add($emitirFaturas);
        $auth->add($calcularValoresIva);
        $auth->add($gerarRelatorios);
        $auth->add($visualizarRelatorios);
        $auth->add($criarClientes);
        $auth->add($editarClientes);
        $auth->add($verClientes);
        $auth->add($eliminarClientes);

        // Associar as permissões aos roles apropriados
        $auth->addChild($clienteRole, $reservarOnline);
        $auth->addChild($clienteRole, $adicionarcarrinhoCompras);
        $auth->addChild($clienteRole, $consultarFaturas);
        $auth->addChild($clienteRole, $classificarecomentarAlojamentos);
        $auth->addChild($clienteRole, $pagarReserva);

        $auth->addChild($funcionarioRole, $reservarPresencial);
        $auth->addChild($funcionarioRole, $criarReservas);
        $auth->addChild($funcionarioRole, $editarReservas);
        $auth->addChild($funcionarioRole, $verReservas);
        $auth->addChild($funcionarioRole, $eliminarReservas);
        $auth->addChild($funcionarioRole, $visualizarRelatorios);
        $auth->addChild($funcionarioRole, $criarClientes);
        $auth->addChild($funcionarioRole, $editarClientes);
        $auth->addChild($funcionarioRole, $verClientes);
        $auth->addChild($funcionarioRole, $eliminarClientes);
        $auth->addChild($funcionarioRole, $calcularValoresIva);

        $auth->addChild($fornecedorRole, $confirmarReserva);
        $auth->addChild($fornecedorRole, $criarAlojamentos);
        $auth->addChild($fornecedorRole, $editarAlojamentos);
        $auth->addChild($fornecedorRole, $eliminarAlojamentos);
        $auth->addChild($fornecedorRole, $detalhesAlojamentos);

        $auth->addChild($adminRole, $emitirFaturas);
        $auth->addChild($adminRole, $gerarRelatorios);
        $auth->addChild($adminRole, $criarAlojamentos);
        $auth->addChild($adminRole, $editarAlojamentos);
        $auth->addChild($adminRole, $detalhesAlojamentos);
        $auth->addChild($adminRole, $eliminarAlojamentos);
        $auth->addChild($adminRole, $criarReservas);
        $auth->addChild($adminRole, $editarReservas);
        $auth->addChild($adminRole, $verReservas);
        $auth->addChild($adminRole, $eliminarReservas);
        $auth->addChild($adminRole, $calcularValoresIva);

        echo "RBAC configuration completed.\n";
    }
}

