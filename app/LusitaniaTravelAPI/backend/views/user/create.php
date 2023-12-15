<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Criar novo User';

?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">User</h3>
    </div>
    <?php $form = ActiveForm::begin(['action' => ['user/store'], 'method' => 'post', 'options' => ['class' => 'container']]); ?>
    <div class="card-body">
        <div class="form-group">
            <?= $form->field($user, 'username')->textInput(['class' => 'form-control'])->label('Username') ?>
        </div>
        <div class="form-group">
            <?= $form->field($profile, 'name')->textInput(['class' => 'form-control'])->label('Nome') ?>
        </div>
        <div class="form-group">
            <?= $form->field($user, 'email')->textInput(['class' => 'form-control'])->label('Email') ?>
        </div>
        <div class="form-group">
            <?= $form->field($profile, 'mobile')->textInput(['class' => 'form-control'])->label('Telefone') ?>
        </div>
        <div class="form-group">
            <?= $form->field($profile, 'street')->textInput(['class' => 'form-control'])->label('Morada') ?>
        </div>
        <div class="form-group">
            <?= $form->field($profile, 'locale')->textInput(['class' => 'form-control'])->label('Localidade') ?>
        </div>
        <div class="form-group">
            <?= $form->field($profile, 'postalCode')->textInput(['class' => 'form-control'])->label('Código Postal') ?>
        </div>
        <div class="form-group">
            <?= $form->field($user, 'status')->dropDownList(
                [
                    1 => '1 - Apagado',
                    9 => '9 - Desativado',
                    10 => '10 - Ativado',
                ],
                ['prompt' => 'Seleciona um', 'class' => 'form-control custom-select']
            )->label('Status') ?>
        </div>
        <div class="form-group">
            <?= $form->field($profile, 'role')->dropDownList(['admin' => 'Admin', 'funcionario' => 'Funcionario', 'fornecedor' => 'Fornecedor', 'cliente' => 'Cliente'], ['prompt' => 'Seleciona um', 'class' => 'form-control custom-select'])->label('Role') ?>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <?= Html::a('Cancelar', ['user/index'], ['class' => 'btn btn-secondary']) ?>
                </div>
                <div class="float-right">
                    <?= Html::submitButton('Criar User', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>