<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Register Form Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h1 class="text-center text-primary text-uppercase mb-4">Register</h1>

                <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control rounded-0', 'placeholder' => 'Username']) ?>

                <?= $form->field($model, 'email')->textInput(['class' => 'form-control rounded-0', 'placeholder' => 'Email']) ?>

                <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control rounded-0', 'placeholder' => 'Password']) ?>

                <?= $form->field($model, 'repeatpassword')->passwordInput(['class' => 'form-control rounded-0', 'placeholder' => 'Repeat Password']) ?>

                <?= $form->field($model, 'name')->textInput(['class' => 'form-control rounded-0', 'placeholder' => 'Name']) ?>

                <?= $form->field($model, 'mobile')->textInput(['class' => 'form-control rounded-0', 'placeholder' => 'Mobile']) ?>

                <?= $form->field($model, 'street')->textInput(['class' => 'form-control rounded-0', 'placeholder' => 'Street']) ?>

                <?= $form->field($model, 'locale')->textInput(['class' => 'form-control rounded-0', 'placeholder' => 'Locale']) ?>

                <?= $form->field($model, 'postalCode')->textInput(['class' => 'form-control rounded-0', 'placeholder' => 'Postal Code']) ?>

                <div class="form-group">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary rounded-0 w-100', 'name' => 'register-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="text-center">
                    <p>Already have an account? <a href="index.php?r=site/login">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Form End -->

