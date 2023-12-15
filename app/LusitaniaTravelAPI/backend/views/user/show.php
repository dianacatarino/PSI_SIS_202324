<?php

use yii\bootstrap5\Html;

$this->title = 'Detalhes do User';
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
        <?= Html::a('Voltar', ['user/index'], ['class' => 'btn btn-secondary']) ?>
    </p>
</div>

<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalhes do User <?= $user->id ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Username</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($user->username) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Nome</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($profile->name) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Email</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($user->email) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Telefone</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($profile->mobile) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Telefone</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($profile->mobile) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Morada</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($profile->street) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Localidade</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($profile->locale) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Código Postal</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($profile->postalCode) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Estado</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($user->status) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Role</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($profile->role) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
</section>