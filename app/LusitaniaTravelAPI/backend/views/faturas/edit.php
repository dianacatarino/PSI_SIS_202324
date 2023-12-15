<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Editar Fatura';

?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Fatura</h3>
    </div>
    <form action="caminho/para/store" method="post" class="container">
        <div class="card-body">
            <div class="form-group">
                <label for="totalfatura" class="control-label">Total Fatura</label>
                <input type="text" id="totalfatura" class="form-control" name="totalfatura">
            </div>
            <div class="form-group">
                <label for="totalsiva" class="control-label">Total S/Iva</label>
                <input type="text" id="totalsiva" class="form-control" name="totalsiva">
            </div>
            <div class="form-group">
                <label for="iva" class="control-label">Iva</label>
                <input type="text" id="iva" class="form-control" name="iva">
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12">
                    <div class="float-left">
                        <?= Html::a('Cancelar', ['faturas/index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                    <div class="float-right">
                        <?= Html::submitButton('Editar Fatura', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>