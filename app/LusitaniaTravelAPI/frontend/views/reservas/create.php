<?php

use yii\helpers\Html;

$this->title = 'Reservar';

// Initialize variables with empty values
$nome = '';
$email = '';
$telemovel = '';
$morada = '';
$localidade = '';
$codigo_postal = '';

// Check if the user is logged in
if (!Yii::$app->user->isGuest) {
    // If logged in, retrieve user details
    $utilizador = Yii::$app->user->identity;
    $nome = $utilizador->name;
    $email = $utilizador->email;
    $telemovel = $utilizador->mobile;
    $morada = $utilizador->street;
    $localidade = $utilizador->locale;
    $codigo_postal = $utilizador->postalCode;
}
?>

<div class="container mt-5">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['site/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="card">
        <div class="card-body">
            <form action="reserva/store.php" method="post">
                <div class="form-group">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= Html::encode($nome) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= Html::encode($email) ?>" required>
                </div>

                <div class="form-group">
                    <label for="telemovel">Telemóvel:</label>
                    <input type="tel" id="telemovel" name="telemovel" class="form-control" value="<?= Html::encode($telemovel) ?>" required>
                </div>

                <div class="form-group">
                    <label for="morada">Morada:</label>
                    <input type="text" id="morada" name="morada" class="form-control" value="<?= Html::encode($morada) ?>" required>
                </div>

                <div class="form-group">
                    <label for="localidade">Localidade:</label>
                    <input type="text" id="localidade" name="localidade" class="form-control" value="<?= Html::encode($localidade) ?>" required>
                </div>

                <div class="form-group">
                    <label for="codigo_postal">Código Postal:</label>
                    <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" value="<?= Html::encode($codigo_postal) ?>" required>
                </div>

                <div class="form-group">
                    <label for="check-in">Check-in:</label>
                    <input type="date" id="check-in" name="check-in" class="form-control" value="2023-12-01" required>
                </div>

                <div class="form-group">
                    <label for="check-out">Check-out:</label>
                    <input type="date" id="check-out" name="check-out" class="form-control" value="2023-12-10" required>
                </div>

                <div class="form-group">
                    <label for="guests">Número de Pessoas:</label>
                    <input type="number" id="guests" name="guests" class="form-control" value="3" min="1" required>
                </div>

                <div class="form-group">
                    <label for="guests">Número de Quartos:</label>
                    <input type="number" id="rooms" name="rooms" class="form-control" value="3" min="1" required>
                </div>
                <div style="height: 20px;"></div>
                <button type="submit" class="btn btn-primary">Reservar</button>
            </form>
        </div>
    </div>
    <div style="height: 20px;"></div>
</div>
