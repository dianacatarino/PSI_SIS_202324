<?php

use yii\helpers\Html;

$this->title = 'Detalhes do Alojamento';

?>

<div class="detalhes-alojamento">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['site/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nome do Alojamento x</h5>

            <table class="table">
                <tbody>
                <tr>
                    <th scope="row">Tipo</th>
                    <td>Tipo de Alojamento</td>
                </tr>
                <tr>
                    <th scope="row">Número de Camas</th>
                    <td>3</td>
                </tr>
                <tr>
                    <th scope="row">Número de Casas de Banho</th>
                    <td>2</td>
                </tr>
                <tr>
                    <th scope="row">Comodidades</th>
                    <td>Wifi</td>
                </tr>
                <tr>
                    <th scope="row">Preço por Noite</th>
                    <td>100 €</td>
                </tr>
                </tbody>
            </table>

            <div style="height: 20px;"></div>

            <div class="mt-3">
                <h5>Comentários/Avaliação</h5>

                <!-- Add a form for adding comments -->
                <form>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Adicionar Comentário:</label>
                        <textarea class="form-control" id="comment" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Avaliação:</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="5">5 estrelas</option>
                            <option value="4">4 estrelas</option>
                            <option value="3">3 estrelas</option>
                            <option value="2">2 estrelas</option>
                            <option value="1">1 estrela</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar Comentário/Avaliação</button>
                </form>

                <div style="height: 20px;"></div>
            </div>

        </div>
    </div>
    <div style="height: 20px;"></div>
</div>