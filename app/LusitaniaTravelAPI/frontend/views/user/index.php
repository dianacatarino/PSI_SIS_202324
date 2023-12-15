<?php

use yii\helpers\Html;

$this->title = 'Conta';
?>
<div class="container text-center">
    <div class="card conta-card">
        <div class="conta-header">
            <?php
            $utilizadorAtual = Yii::$app->user->identity;

            echo '<img src="/LusitaniaTravel/frontend/public/img/logo_icon.png" alt="Foto de Perfil">';

            if (!Yii::$app->user->isGuest) {
                $username = ($utilizadorAtual->username);
                echo '<h2>' . Html::label('Username:')  . ' ' . Html::encode($username) . '</h2>';
                echo '<p>' . Html::label('Nome:') . ' ' . Html::encode($utilizadorAtual->name) . '</p>';
                echo '<p>' . Html::label('E-mail:') . ' ' . Html::encode($utilizadorAtual->email) . '</p>';
                echo '<p>' . Html::label('Telefone:') . ' ' . Html::encode($utilizadorAtual->mobile) . '</p>';
                echo '<p>' . Html::label('Morada:') . ' ' . Html::encode($utilizadorAtual->street) . '</p>';
                echo '<p>' . Html::label('Localidade:') . ' ' . Html::encode($utilizadorAtual->locale) . '</p>';
                echo '<p>' . Html::label('Código Postal:') . ' ' . Html::encode($utilizadorAtual->postalCode) . '</p>';
            }
            ?>
        </div>

        <div class="conta-links">
            <button onclick="window.location.href='index.php?r=comentarios/index'" class="btn btn-primary">
                <i class="fas fa-comments"></i> Os Meus Comentários
            </button>
            <button onclick="window.location.href='index.php?r=user/definicoes'" class="btn btn-primary">
                <i class="fas fa-cog"></i> Definições
            </button>
            <button onclick="window.location.href='index.php?r=faturas/index'" class="btn btn-primary">
                <i class="fas fa-file-invoice-dollar"></i> As Minhas Faturas
            </button>
        </div>
        <div style="height: 20px;"></div>
    </div>
    <div style="height: 20px;"></div>
</div>

