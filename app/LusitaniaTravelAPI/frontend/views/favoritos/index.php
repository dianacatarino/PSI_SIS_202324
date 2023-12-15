<?php

use yii\helpers\Html;

$this->title = 'Favoritos';
?>

<div class="favoritos-container">
    <h1 class="mb-4">Meus Favoritos</h1>

    <!-- Exemplo de Favorito -->
    <div class="card favorito-card">
        <div class="card-body">
            <h5 class="card-title">Nome do Alojamento x</h5>
            <?= Html::a('Detalhes', ['favoritos/view'], ['class' => 'btn btn-primary']) ?>
            <button class="btn btn-danger">
                <i class="fas fa-heart heart-icon"></i> Remover dos Favoritos
            </button>
        </div>
    </div>

</div>
