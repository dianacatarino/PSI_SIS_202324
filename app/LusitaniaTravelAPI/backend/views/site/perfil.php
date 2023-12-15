<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Conta';

?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="/LusitaniaTravel/backend/web/dist/img/user2-160x160.jpg"
                                 alt="User profile picture">
                        </div>

                        <?php
                        $utilizadorAtual = Yii::$app->user->identity;
                        $utilizadorAtual->load('profile');
                        ?>

                        <h3 class="profile-username text-center"><?= Html::encode($utilizadorAtual->username) ?></h3>
                        <p class="text-muted text-center"><?= Html::encode($utilizadorAtual->profile->role) ?></p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Nome</b> <a class="float-right"><?= Html::encode($utilizadorAtual->profile->name) ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right"><?= Html::encode($utilizadorAtual->email) ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Telefone</b> <a class="float-right"><?= Html::encode($utilizadorAtual->profile->mobile) ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Morada</b> <a class="float-right"><?= Html::encode($utilizadorAtual->profile->street) ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Localidade</b> <a class="float-right"><?= Html::encode($utilizadorAtual->profile->locale) ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Código Postal</b> <a class="float-right"><?= Html::encode($utilizadorAtual->profile->postalCode) ?></a>
                            </li>
                        </ul>

                        <a href="<?= Url::to(['site/definicoes']) ?>" class="btn btn-primary btn-block"><b>Definições</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

