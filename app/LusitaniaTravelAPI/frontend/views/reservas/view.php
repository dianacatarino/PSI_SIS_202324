<?php

use yii\helpers\Html;

$this->title = 'Detalhes da Reserva';

?>

<div class="detalhes-reserva">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['reservas/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Reserva #1</h5>

            <table class="table detalhes-reserva-table">
                <tbody>
                <tr>
                    <th scope="row">Tipo</th>
                    <td>Online</td>
                </tr>
                <tr>
                    <th scope="row">Check-in</th>
                    <td>2023-01-15</td>
                </tr>
                <tr>
                    <th scope="row">Check-out</th>
                    <td>2023-01-20</td>
                </tr>
                <tr>
                    <th scope="row">Número de Quartos</th>
                    <td>2</td>
                </tr>
                <tr>
                    <th scope="row">Número de Clientes</th>
                    <td>4</td>
                </tr>
                <tr>
                    <th scope="row">Valor</th>
                    <td>200 €</td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

