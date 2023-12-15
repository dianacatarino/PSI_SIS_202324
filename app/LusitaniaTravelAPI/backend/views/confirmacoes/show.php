<?php

use yii\helpers\Html;

$this->title = 'Detalhes da Confirmação';
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
        <?= Html::a('Voltar', ['confirmacao/index'], ['class' => 'btn btn-secondary']) ?>
    </p>
</div>

<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalhes da Confirmação <?= $confirmacao->id ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Estado da Confirmação</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($confirmacao->estado) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Data da Confirmação</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($confirmacao->data_confirmacao) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">ID da Reserva</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($confirmacao->reserva_id) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">ID do Alojamento</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($confirmacao->alojamento_id) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</section>

