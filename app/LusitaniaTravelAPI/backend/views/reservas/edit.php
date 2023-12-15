<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Editar Reserva';

?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Reserva</h3>
    </div>
    <form action="caminho/para/store" method="post" class="container">
        <div class="card-body">
            <div class="form-group">
                <label for="check-in" class="control-label">Check-in</label>
                <input type="date" id="check-in" class="form-control" name="check-in">
            </div>
            <div class="form-group">
                <label for="check-out" class="control-label">Check-out</label>
                <input type="date" id="check-out" class="form-control" name="check-out">
            </div>
            <div class="form-group">
                <label for="pessoas" class="control-label">Pessoas</label>
                <input type="number" id="pessoas" class="form-control" name="pessoas">
            </div>
            <div class="form-group">
                <label for="quartos" class="control-label">Quartos</label>
                <input type="number" id="quartos" class="form-control" name="quartos">
            </div>
            <div class="form-group">
                <label for="preco_noite" class="control-label">Preço por noite</label>
                <input type="text" id="preco_noite" class="form-control" name="preco_noite">
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12">
                    <div class="float-left">
                        <?= Html::a('Cancelar', ['reservas/index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                    <div class="float-right">
                        <?= Html::submitButton('Editar Reserva', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


