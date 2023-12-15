<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Criar novo Alojamento';

?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Alojamento</h3>
    </div>

    <?php $form = ActiveForm::begin(['action' => ['alojamentos/create'], 'method' => 'post', 'options' => ['class' => 'container']]); ?>
    <div class="card-body">
        <div class="form-group">
            <?= $form->field($fornecedor, 'responsavel')->textInput(['class' => 'form-control'])->label('Responsável') ?>
        </div>
        <div class="form-group">
            <?= $form->field($fornecedor, 'tipo')->dropDownList(
                [
                    'Hotel' => 'Hotel',
                    'Alojamento Local' => 'Alojamento Local',
                    'Resort' => 'Resort',
                ],
                ['prompt' => 'Selecione um tipo', 'class' => 'form-control']
            )->label('Tipo') ?>
        </div>
        <div class="form-group">
            <?= $form->field($fornecedor, 'nome_alojamento')->textInput(['class' => 'form-control'])->label('Nome') ?>
        </div>
        <div class="form-group">
            <?= $form->field($fornecedor, 'localizacao_alojamento')->textInput(['class' => 'form-control'])->label('Localização') ?>
        </div>
        <div class="form-group">
            <?= $form->field($fornecedor, 'acomodacoes_alojamento')->checkboxList(
                [
                    'Cama de Casal' => 'Cama de Casal',
                    'Cama de Solteiro' => 'Cama de Solteiro',
                    'Wi-Fi' => 'Wi-Fi',
                    'TV' => 'TV',
                    'AC' => 'AC',
                    'WC Privativa' => 'WC Privativa',
                    'Pequeno Almoço' => 'Pequeno Almoço',
                    'Quartos Familiares' => 'Quartos Familiares',
                    'Piscina' => 'Piscina',
                    'Estacionamento' => 'Estacionamento',
                ],
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checked = $checked ? 'checked' : '';
                        return "<label class='checkbox-inline'><input type='checkbox' $checked name='$name' value='$value'> $label</label>";
                    },
                ]
            )->label('Acomodações');
            ?>
        </div>
        <div class="form-group">
            <?= $form->field($fornecedor, 'imagens[]')->fileInput(['multiple' => true])->label('Imagens') ?>
            <div id="image-preview">
                <?php
                // Exibir imagens existentes (caso existam)
                foreach ($fornecedor->imagens as $imagem) {
                    echo Html::img($imagem->filename, ['class' => 'img-thumbnail', 'style' => 'max-width:100px; margin-right: 5px;']);
                }
                ?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <?= Html::a('Cancelar', ['alojamentos/index'], ['class' => 'btn btn-secondary']) ?>
                </div>
                <div class="float-right">
                    <?= Html::submitButton('Criar Alojamento', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
