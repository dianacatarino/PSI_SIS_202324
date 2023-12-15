<?php
use yii\helpers\Html;

$this->title = 'Gestão de Faturas';
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
        <?= Html::a('Criar nova Fatura', ['faturas/create'], ['class' => 'btn btn-info']) ?>
    </p>
</div>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Faturas</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 5%">Id</th>
                    <th style="width: 5%">Total Fatura</th>
                    <th style="width: 5%">Total S/Iva</th>
                    <th style="width: 5%">Iva</th>
                    <th style="width: 1%">Ações</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>100€</td>
                    <td>90€</td>
                    <td>10%</td>
                    <td class="project-actions text-right">
                        <div class="btn-group">
                            <?= Html::a('<i class="fas fa-folder"></i>', ['faturas/show'], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['faturas/edit'], ['class' => 'btn btn-info btn-sm']) ?>
                            <?= Html::a('<i class="fas fa-trash"></i>', ['faturas/delete'], [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

