<?php

use yii\helpers\Html;

$this->title = 'Gestão dos Comentários';
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
            <h3 class="card-title">Comentários</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">ID do Alojamento</th>
                    <th style="width: 1%">Título</th>
                    <th style="width: 5%">Comentário da estadia</th>
                    <th style="width: 5%">ID do Cliente</th>
                    <th style="width: 1%">Data do Comentário</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($comentarios as $comentario): ?>
                    <tr>
                        <td><?= $comentario->id ?></td>
                        <td><?= Html::encode($comentario->fornecedor_id) ?></td>
                        <td><?= Html::encode($comentario->titulo) ?></td>
                        <td><?= Html::encode($comentario->descricao) ?></td>
                        <td><?= Html::encode($comentario->cliente_id) ?></td>
                        <td><?= Html::encode($comentario->data_comentario)?></td>
                        <td class="project-actions text-right">
                            <div class="btn-group">
                                <?= Html::a('<i class="fas fa-folder"></i>', ['alojamentos/show', 'id' => $comentario->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['alojamentos/delete', 'id' => $comentario->id], [
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
