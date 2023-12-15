<?php

use yii\helpers\Html;

$this->title = 'Definições';
?>

<div class="definicoes">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['user/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <h1>Definições</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Configurações de Conta</h5>

            <form class="mb-4">
                <div class="mb-3">
                    <label for="inputProfilePicture" class="form-label">Foto de Perfil</label>
                    <input type="file" class="form-control" id="inputProfilePicture" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="inputUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="inputUsername" placeholder="Nome de Utilizador">
                </div>

                <div class="mb-3">
                    <label for="inputName" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="inputName" placeholder="Nome">
                </div>

                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                </div>

                <div class="mb-3">
                    <label for="inputPhone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="inputPhone" placeholder="Telefone">
                </div>

                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="Nova Senha">
                </div>

                <div class="mb-3">
                    <label for="inputRepeatPassword" class="form-label">Repetir Nova Senha</label>
                    <input type="repeatpassword" class="form-control" id="inputRepeatPassword" placeholder="Repetir Nova Senha">
                </div>

                <div class="mb-3">
                    <label for="inputAddress" class="form-label">Morada</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="Morada">
                </div>

                <div class="mb-3">
                    <label for="inputCity" class="form-label">Localidade</label>
                    <input type="text" class="form-control" id="inputCity" placeholder="Localidade">
                </div>

                <div class="mb-3">
                    <label for="inputPostalCode" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="inputPostalCode" placeholder="Código Postal">
                </div>

                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>

        </div>
    </div>
    <div style="height: 20px;"></div>
</div>



