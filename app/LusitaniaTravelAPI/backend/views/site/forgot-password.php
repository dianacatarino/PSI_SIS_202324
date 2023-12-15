<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Forgot-password';

?>

<!-- /.login-logo -->
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Esqueceu-se da sua password? Aqui pode restaurá-la</p>

        <form action="recover-password.php" method="post">
            <div class="input-group mb-3">
                <input type="username" class="form-control" placeholder="Username">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Pedir nova password</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <p class="mt-3 mb-1">
            <a href="login.php">Entrar</a>
        </p>
        <p class="mb-0">
            <a href="register.php" class="text-center">Não tenho conta</a>
        </p>
    </div>
    <!-- /.login-card-body -->
</div>