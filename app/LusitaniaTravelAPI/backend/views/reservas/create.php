<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Criar nova Reserva';

?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Reserva</h3>
    </div>
    <form action="caminho/para/store" method="post" class="container">
        <div class="card-body">
            <div class="form-group">
                <label for="reserva-check-in" class="control-label">Alojamento nº</label>
                <input type="text" id="reserva-alojamento" class="form-control" name="Reserva[alojamento]">
            </div>
            <div class="form-group">
                <label for="reserva-check-in" class="control-label">Check-in</label>
                <input type="date" id="reserva-check-in" class="form-control" name="Reserva[check-in]">
            </div>
            <div class="form-group">
                <label for="reserva-check-out" class="control-label">Check-out</label>
                <input type="date" id="reserva-check-out" class="form-control" name="Reserva[check-out]">
            </div>
            <div class="form-group">
                <label for="reserva-pessoas" class="control-label">Pessoas</label>
                <input type="number" id="reserva-pessoas" class="form-control" name="Reserva[pessoas]">
            </div>
            <div class="form-group">
                <label for="reserva-quartos" class="control-label">Quartos</label>
                <input type="number" id="reserva-quartos" class="form-control" name="Reserva[quartos]">
            </div>
            <div class="form-group">
                <label for="reserva-precopornoite" class="control-label">Preço por noite</label>
                <input type="text" id="reserva-precopornoite" class="form-control" name="Reserva[precopornoite]">
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12">
                    <div class="float-left">
                        <?= Html::a('Cancelar', ['reservas/index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                    <div class="float-right">
                        <?= Html::submitButton('Criar Reserva', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

