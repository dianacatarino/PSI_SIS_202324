<?php

use yii\helpers\Html;

$this->title = 'Gestão da Empresa';
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
        <?= Html::a('Criar nova empresa', ['empresa/create'], ['class' => 'btn btn-info']) ?>
    </p>
</div>
<?php $error = Yii::$app->session->getFlash('error');
if ($error !== null) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
}
$success = Yii::$app->session->getFlash('success');
if ($success !== null) {
    echo '<div class="alert alert-success">' . $success . '</div>';
} ?>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Empresa</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">Id</th>
                    <th style="width: 5%">Sede</th>
                    <th style="width: 5%">Capital Social</th>
                    <th style="width: 1%">Email</th>
                    <th style="width: 10%">Morada</th>
                    <th style="width: 1%">Localidade</th>
                    <th style="width: 1%">NIF</th>
                    <th style="width: 1%">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($empresas as $empresa): ?>
                    <tr>
                        <td><?= $empresa->id ?></td>
                        <td><?= Html::encode($empresa->sede) ?></td>
                        <td><?= Html::encode($empresa->capitalsocial) ?></td>
                        <td><?= Html::encode($empresa->email) ?></td>
                        <td><?= Html::encode($empresa->morada) ?></td>
                        <td><?= Html::encode($empresa->localidade) ?></td>
                        <td><?= Html::encode($empresa->nif) ?></td>
                        <td class="project-actions text-right">
                            <div class="btn-group">
                                <?= Html::a('<i class="fas fa-folder"></i>', ['empresa/show', 'id' => $empresa->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['empresa/edit', 'id' => $empresa->id], ['class' => 'btn btn-info btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['empresa/delete', 'id' => $empresa->id], [
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
