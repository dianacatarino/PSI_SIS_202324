<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Editar Empresa';

?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"> Empresa</h3>
    </div>
    <?php $form = ActiveForm::begin(['action' => ['empresa/update', 'id' => $empresa->id], 'method' => 'post', 'options' => ['class' => 'container']]); ?>
    <div class="card-body">
        <div class="form-group">
            <?= $form->field($empresa, 'sede')->textInput(['class' => 'form-control'])->label('Sede') ?>
        </div>
        <div class="form-group">
            <?= $form->field($empresa, 'capitalsocial')->textInput(['class' => 'form-control'])->label('Capital Social') ?>
        </div>
        <div class="form-group">
            <?= $form->field($empresa, 'email')->textInput(['class' => 'form-control'])->label('Email') ?>
        </div>
        <div class="form-group">
            <?= $form->field($empresa, 'morada')->textInput(['class' => 'form-control'])->label('Morada') ?>
        </div>
        <div class="form-group">
            <?= $form->field($empresa, 'localidade')->textInput(['class' => 'form-control'])->label('Localidade') ?>
        </div>
        <div class="form-group">
            <?= $form->field($empresa, 'nif')->textInput(['class' => 'form-control'])->label('NIF') ?>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <?= Html::a('Cancelar', ['empresa/index'], ['class' => 'btn btn-secondary']) ?>
                </div>
                <div class="float-right">
                    <?= Html::submitButton('Editar Empresa', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
