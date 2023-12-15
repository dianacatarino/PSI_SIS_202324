<?php

use yii\bootstrap5\Html;

$this->title = 'Detalhes da Avaliação';
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
        <?= Html::a('Voltar', ['avaliacoes/index'], ['class' => 'btn btn-secondary']) ?>
    </p>
</div>

<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalhes da Avaliação<?= $avaliacao->id ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">ID do Alojamento</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($avaliacao->fornecedor_id) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Avaliação Geral (0-10)</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($avaliacao->classificacao) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">ID do Cliente</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($avaliacao->cliente_id) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Data da Avaliação</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($avaliacao->data_avaliacao) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
</section>
