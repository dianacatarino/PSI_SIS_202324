<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Definições';

?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <!-- Definições Form -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- Definições Form Content -->
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" placeholder="Username">
                            </div>

                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" placeholder="Nome">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email">
                            </div>

                            <div class="form-group">
                                <label for="senhaAtual">Senha Atual</label>
                                <input type="password" class="form-control" id="senhaAtual" placeholder="Senha Atual">
                            </div>

                            <div class="form-group">
                                <label for="novaSenha">Nova Senha</label>
                                <input type="password" class="form-control" id="novaSenha" placeholder="Nova Senha">
                            </div>

                            <div class="form-group">
                                <label for="confirmarSenha">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" id="confirmarSenha" placeholder="Confirmar Nova Senha">
                            </div>

                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control" id="telefone" placeholder="Telefone">
                            </div>

                            <div class="form-group">
                                <label for="morada">Morada</label>
                                <input type="text" class="form-control" id="morada" placeholder="Morada">
                            </div>

                            <div class="form-group">
                                <label for="localidade">Localidade</label>
                                <input type="text" class="form-control" id="localidade" placeholder="Localidade">
                            </div>

                            <div class="form-group">
                                <label for="codigoPostal">Código Postal</label>
                                <input type="text" class="form-control" id="codigoPostal" placeholder="Código Postal">
                            </div>

                            <div class="form-group">
                                <div class="float-left">
                                    <?= Html::a('Cancelar', ['site/perfil'], ['class' => 'btn btn-secondary']) ?>
                                </div>
                                <div class="float-right">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

