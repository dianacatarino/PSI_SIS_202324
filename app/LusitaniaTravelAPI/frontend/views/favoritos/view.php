<?php

use yii\helpers\Html;

$this->title = 'Detalhes do Favorito';

?>

<div class="detalhes-favorito">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['favoritos/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Favorito #1</h5>

            <table class="table detalhes-favorito-table">
                <tbody>
                <tr>
                    <th scope="row">Nome do Alojamento</th>
                    <td>Lorem impsum</td>
                </tr>
                <tr>
                    <th scope="row">Descrição do Alojamento</th>
                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</td>
                </tr>
                <tr>
                    <th scope="row">Data Favorito</th>
                    <td>2023-01-15</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

