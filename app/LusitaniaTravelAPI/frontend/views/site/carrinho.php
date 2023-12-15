<?php

use yii\helpers\Html;

$this->title = 'Carrinho de Compras';
?>
<div class="container mt-4">
    <!-- Adicione a classe "card" do Bootstrap -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            Carrinho de Compras
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="thead-dark">
                <tr>
                    <th>Reserva</th>
                    <th>Data</th>
                    <th>Preço</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Quarto Individual</td>
                    <td>2023-12-01</td>
                    <td>100€</td>
                </tr>
                <tr>
                    <td>Suíte Deluxe</td>
                    <td>2023-12-10</td>
                    <td>200€</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <td>300€</td>
                </tr>
                </tfoot>
            </table>

            <div class="text-right p-3">
                <p class="font-weight-bold">Total a Pagar: 300€</p>
                <a href="#" class="btn btn-success">Finalizar Compra</a>
            </div>
        </div>
    </div>
</div>

