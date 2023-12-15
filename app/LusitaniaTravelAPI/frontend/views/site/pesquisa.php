<?php

use yii\helpers\Html;

$this->title = 'Pesquisa';
?>
<h1> Pesquisar </h1>
<!-- Search Start -->
<div class="container-fluid search pb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="bg-white shadow" style="padding: 35px;">
            <form action="caminho/para/processar_pesquisa" method="GET" class="d-flex align-items-center">
                <input type="text" class="form-control me-2" name="q" placeholder="Digite sua pesquisa">
                <button type="submit" class="btn btn-primary ms-2">Pesquisar</button>
            </form>
        </div>
    </div>
</div>
<!-- Search End -->


