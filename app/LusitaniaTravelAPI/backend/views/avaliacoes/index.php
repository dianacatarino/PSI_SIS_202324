<?php

use yii\helpers\Html;

$this->title = 'Gestão dos Avaliações';
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Avaliações</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">ID do Alojamento</th>
                    <th style="width: 5%">Avaliação Geral (0-10)</th>
                    <th style="width: 5%">ID do Cliente</th>
                    <th style="width: 5%">Data da Avaliação</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($avaliacoes as $avaliacao): ?>
                    <tr>
                        <td><?= $avaliacao->id ?></td>
                        <td><?= Html::encode($avaliacao->fornecedor_id) ?></td>
                        <td><?= Html::encode($avaliacao->classificacao) ?></td>
                        <td><?= Html::encode($avaliacao->cliente_id) ?></td>
                        <td><?= Html::encode($avaliacao->data_avaliacao) ?></td>
                        <td class="project-actions text-right">
                            <div class="btn-group">
                                <?= Html::a('<i class="fas fa-folder"></i>', ['avaliacoes/show', 'id' => $avaliacao->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['avaliacoes/delete', 'id' => $avaliacao->id], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

