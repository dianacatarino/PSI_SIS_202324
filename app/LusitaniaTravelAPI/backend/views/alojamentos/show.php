<?php

use yii\bootstrap5\Html;

$this->title = 'Detalhes do Alojamento';
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
        <?= Html::a('Voltar', ['alojamentos/index'], ['class' => 'btn btn-secondary']) ?>
    </p>
</div>

<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalhes do Alojamento <?= $fornecedor->id ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Responsável</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($fornecedor->responsavel) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Tipo</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($fornecedor->tipo) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Nome</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($fornecedor->nome_alojamento) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Localização</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($fornecedor->localizacao_alojamento) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Acomodações</span>
                                    <span class="info-box-number text-center text-muted mb-0"><?= Html::encode($fornecedor->acomodacoes_alojamento) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Imagens</span>
                                    <div class="row justify-content-center align-items-center">
                                        <?php foreach ($fornecedor->imagens as $imagem): ?>
                                            <div class="col-6 col-md-4">
                                                <?= Html::img($imagem->filename, ['class' => 'img-thumbnail', 'style' => 'max-width:100%; margin-bottom:10px;']); ?>
                                            </div>
                                        <?php endforeach; ?>
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