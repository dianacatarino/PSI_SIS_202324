<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Forgot Password';

?>

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Esqueceu-se da sua password? Aqui pode restaurá-la</p>

        <?php $form = ActiveForm::begin(['id' => 'forgot-password-form']); ?>

        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email'])->label(false) ?>

        <div class="row">
            <div class="col-12">
                <?= Html::submitButton('Pedir nova password', ['class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="mt-3 mb-1">
            <?= Html::a('Entrar', ['site/login']) ?>
        </p>
        <p class="mb-0">
            <?= Html::a('Não tenho conta', ['site/register'], ['class' => 'text-center']) ?>
        </p>
    </div>
</div>
