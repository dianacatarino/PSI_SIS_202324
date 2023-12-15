<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = 'Editar Alojamento';

// Adicione a biblioteca jQuery (se ainda não estiver incluída)
$this->registerJsFile('https://code.jquery.com/jquery-3.6.4.min.js', ['position' => View::POS_HEAD]);

// Registre o script JavaScript para lidar com a remoção de imagens
$this->registerJs("
    $(document).ready(function() {
        // Adicione um ouvinte de evento para os botões de remoção
        $('.remove-image-btn').on('click', function() {
            // Remova o bloco da imagem e o campo de entrada do formulário
            var imageBlock = $(this).closest('.image-block');
            imageBlock.remove();
        });
    });
", View::POS_END);
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Alojamento</h3>
    </div>

    <?php $form = ActiveForm::begin(['action' => ['alojamentos/edit', 'id' => $fornecedor->id], 'method' => 'post', 'options' => ['class' => 'container']]); ?>
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
            <?= $form->field($fornecedor, 'nome_alojamento')->textInput(['class' => 'form-control'])->label('Nome do Alojamento') ?>
        </div>
        <div class="form-group">
            <?= $form->field($fornecedor, 'localizacao_alojamento')->textInput(['class' => 'form-control'])->label('Localização do Alojamento') ?>
        </div>
        <div class="form-group">
            <?php
            $acomodacoesSelecionadas = !empty($fornecedor->acomodacoes_alojamento)
                ? explode(';', $fornecedor->acomodacoes_alojamento)
                : [];

            echo $form->field($fornecedor, 'acomodacoes_alojamento[]')->checkboxList(
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
                    'item' => function ($index, $label, $name, $checked, $value) use ($acomodacoesSelecionadas) {
                        $checked = in_array($value, $acomodacoesSelecionadas);
                        $checked = $checked ? 'checked' : '';
                        return "<label class='checkbox-inline'><input type='checkbox' $checked name='$name' value='$value'> $label</label>";
                    },
                ]
            )->label('Acomodações');
            ?>
        </div>
        <div class="form-group">
            <?= $form->field($fornecedor, 'imagens[]')->fileInput(['multiple' => true])->label('Imagens') ?>

            <!-- Exibir imagens existentes com botão de remoção -->
            <div id="image-preview">
                <?php foreach ($fornecedor->imagens as $key => $imagem): ?>
                    <div class="image-block">
                        <?= Html::img($imagem->filename, ['class' => 'img-thumbnail', 'style' => 'max-width:100px; margin-right: 5px;']); ?>
                        <?= Html::a('Remover', ['alojamentos/remover-imagem', 'id' => $fornecedor->id, 'key' => $key], [
                            'class' => 'btn btn-danger remove-image-btn',
                            'data-confirm' => 'Tem certeza que deseja remover esta imagem?',
                        ]); ?>
                    </div>
                <?php endforeach; ?>
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
                    <?= Html::submitButton('Editar Alojamento', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


