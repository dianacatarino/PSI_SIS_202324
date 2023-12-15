<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\RegistrationForm $model */  // Assuming you have a RegistrationForm model

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Register';
?>

<div class="register-box" style="max-width: 400px; margin: 0 auto;">
    <div class="register-logo">
        <a href="<?= Yii::$app->homeUrl ?>"><b>Lusitânia</b> Travel</a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg"><i class="fas fa-user-plus"></i> Register a new membership</p>

            <?php $form = ActiveForm::begin(['id' => 'register']); ?>

            <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username'])->label('<i class="fas fa-user"></i> Username') ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label('<i class="fas fa-lock"></i> Password') ?>

            <?= $form->field($model, 'repeatPassword')->passwordInput(['placeholder' => 'Repeat password'])->label('<i class="fas fa-lock"></i> Repeat Password') ?>

            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Name'])->label('<i class="fas fa-user"></i> Name') ?>

            <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email'])->label('<i class="fas fa-envelope"></i> Email') ?>

            <?= $form->field($model, 'mobile')->textInput(['placeholder' => 'Mobile'])->label('<i class="fas fa-phone"></i> Mobile') ?>

            <?= $form->field($model, 'street')->textInput(['placeholder' => 'Street'])->label('<i class="fas fa-map-marker"></i> Street') ?>

            <?= $form->field($model, 'locale')->textInput(['placeholder' => 'Locale'])->label('<i class="fas fa-map"></i> Locale') ?>

            <?= $form->field($model, 'postalCode')->textInput(['placeholder' => 'Postal Code'])->label('<i class="fas fa-envelope"></i> Postal Code') ?>

            <div class="row">
                <div class="col-8">
                    <?= $form->field($model, 'terms')->checkbox(['id' => 'agreeTerms'])->label('Accept Terms') ?>
                </div>
                <div class="col-4">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <a href="<?= Yii::$app->urlManager->createUrl(['site/login']) ?>" class="text-center"><i class="fas fa-sign-in-alt"></i> I already have a membership</a>
        </div>
    </div>
</div>

