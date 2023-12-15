<?php

use yii\helpers\Html;

$this->title = 'Gestão dos Alojamentos';
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

<div class="col-sm-6">
    <p>
        <?= Html::a('Criar novo Alojamento', ['alojamentos/create'], ['class' => 'btn btn-info']) ?>
    </p>
</div>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Alojamentos</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">Id</th>
                    <th style="width: 5%">Responsável</th>
                    <th style="width: 5%">Tipo</th>
                    <th style="width: 5%">Nome</th>
                    <th style="width: 5%">Localização</th>
                    <th style="width: 10%">Acomodações</th>
                    <th style="width: 5%">Imagem</th>
                    <th style="width: 1%">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($fornecedores as $fornecedor): ?>
                    <tr>
                        <td><?= $fornecedor->id ?></td>
                        <td><?= Html::encode($fornecedor->responsavel) ?></td>
                        <td><?= Html::encode($fornecedor->tipo) ?></td>
                        <td><?= Html::encode($fornecedor->nome_alojamento) ?></td>
                        <td><?= Html::encode($fornecedor->localizacao_alojamento) ?></td>
                        <td><?= Html::encode($fornecedor->acomodacoes_alojamento) ?></td>
                        <td>
                            <?php
                            $imagens = $fornecedor->imagens;
                            if (!empty($imagens)) {
                                $imagem = $imagens[0]; // Ajuste conforme necessário, dependendo da lógica que você deseja aplicar
                                echo Html::img($imagem->filename, ['class' => 'img-thumbnail', 'style' => 'max-width:100px;']);
                            }
                            ?>
                        </td>
                        <td class="project-actions text-right">
                            <div class="btn-group">
                                <?= Html::a('<i class="fas fa-folder"></i>', ['alojamentos/show', 'id' => $fornecedor->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['alojamentos/edit', 'id' => $fornecedor->id], ['class' => 'btn btn-info btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['alojamentos/delete', 'id' => $fornecedor->id], [
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
