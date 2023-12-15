<?php

use yii\helpers\Html;

$this->title = 'Reservas';
?>

<div class="reserva">
    <h1>Reserva</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Reserva #1</h5>

            <table class="table reserva-table">
                <tbody>
                <tr>
                    <th scope="row">Check-in</th>
                    <td>2023-01-01</td>
                </tr>
                <tr>
                    <th scope="row">Check-out</th>
                    <td>2023-01-10</td>
                </tr>
                <tr>
                    <th scope="row">Valor</th>
                    <td>200 €</td>
                </tr>
                <!-- Adicione mais informações conforme necessário -->
                </tbody>
            </table>

            <?= Html::a('Detalhes', ['reservas/view'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
