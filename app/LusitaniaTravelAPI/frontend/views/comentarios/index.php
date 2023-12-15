<?php

use yii\helpers\Html;

$this->title = 'Comentários';
?>

<div class="comentarios">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['user/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <h1>Comentários</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Comentário #1</h5>

            <table class="table comentarios-table">
                <tbody>
                <tr>
                    <th scope="row">Data</th>
                    <td>2023-01-15</td>
                </tr>
                <tr>
                    <th scope="row">Avaliação</th>
                    <td>5 estrelas</td>
                </tr>
                </tbody>
            </table>

            <?= Html::a('Detalhes', ['comentarios/view'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

