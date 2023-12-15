<?php

use yii\helpers\Html;

$this->title = 'Faturas';
?>

<div class="faturas">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['user/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <h1>Faturas</h1>


    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Fatura #1</h5>

            <table class="table faturas-table">
                <tbody>
                <tr>
                    <th scope="row">Data</th>
                    <td>2023-02-20</td>
                </tr>
                <tr>
                    <th scope="row">Valor</th>
                    <td>200 €</td>
                </tr>
                <!-- Adicione mais informações conforme necessário -->

                <tr>
                    <th scope="row">Download</th>
                    <td>
                        <a href="url_do_download" class="btn btn-success">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <?= Html::a('Detalhes', ['faturas/view'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
