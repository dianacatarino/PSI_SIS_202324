<?php

use yii\helpers\Html;

$this->title = 'Gestão dos Utilizadores';
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
        <?= Html::a('Criar novo utilizador', ['user/create'], ['class' => 'btn btn-info']) ?>
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
            <h3 class="card-title">Utilizadores </h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">Id</th>
                    <th style="width: 1%">Username</th>
                    <th style="width: 1%">Nome</th>
                    <th style="width: 1%">Email</th>
                    <th style="width: 1%">Telefone</th>
                    <th style="width: 15%">Morada</th>
                    <th style="width: 1%">Localidade</th>
                    <th style="width: 15%">Código Postal</th>
                    <th style="width: 1%">Status</th>
                    <th style="width: 1%">Role</th>
                    <th style="width: 1%">User Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <?php
                    // Certifique-se de que o perfil está carregado
                    $user->load('profile');
                    ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= Html::encode($user->username) ?></td>
                        <td><?= Html::encode($user->profile->name) ?></td>
                        <td><?= Html::encode($user->email) ?></td>
                        <td><?= Html::encode($user->profile->mobile) ?></td>
                        <td><?= Html::encode($user->profile->street) ?></td>
                        <td><?= Html::encode($user->profile->locale) ?></td>
                        <td><?= Html::encode($user->profile->postalCode) ?></td>
                        <td><?= Html::encode($user->status) ?></td>
                        <td><?= Html::encode($user->profile->role) ?></td>
                        <td class="project-actions text-right">
                            <div class="btn-group">
                                <?= Html::a('<i class="fas fa-folder"></i>', ['user/show', 'id' => $user->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['user/edit', 'id' => $user->id], ['class' => 'btn btn-info btn-sm']) ?>
                                <?= Html::a('<i class="fas fa-trash"></i>', ['user/delete', 'id' => $user->id], [
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
