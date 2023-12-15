<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Emitir Fatura';

?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <i class="fas fa-globe"></i> Fatura
                                <small class="float-right"><?= date('d-m-Y') ?></small>
                            </h4>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>
                                <tr>
                                    <strong><td> Sede </td></strong><br>
                                    <td> Capital Social </td>
                                    <td> Email </td>
                                    <td> Morada </td><br>
                                    <td> Localidade </td><br>
                                    Nif: <td> </td><br>
                                    Email: <td> </td>
                                </tr>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong>Cliente</strong>
                                <a href="#" class="btn btn-info" role="button">Selecionar Cliente</a>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Fatura #007612</b><br>
                            <br>
                            <b>Fatura ID: </b> 1 <br>
                            <b>Data Pagamento: </b><?= date('d-m-Y') ?><br>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Referência</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Valor Iva</th>
                                    <th>Sub Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>60€</td>
                                    <td>10%</td>
                                    <td>100€</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">
                            <p class="lead">Métodos de Pagamento:</p>
                            <img src="/LusitaniaTravel/backend/web/dist/img/credit/visa.png" alt="Visa">
                            <img src="/LusitaniaTravel/backend/web/dist/img/credit/mastercard.png" alt="Mastercard">
                            <img src="/LusitaniaTravel/backend/web/dist/img/credit/american-express.png" alt="American Express">
                            <img src="/LusitaniaTravel/backend/web/dist/img/credit/paypal2.png" alt="Paypal">
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Iva Total:</th>
                                        <td>90€</td>
                                    </tr>
                                    <tr>
                                        <th>Valor Total:</th>
                                        <td>100€</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>200€</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-12">
                            <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submeter
                            </button>
                            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                <i class="fas fa-download"></i> Generate PDF
                            </button>
                        </div>
                    </div>

                    <!-- Rodapé -->
                    <footer class="invoice-footer">
                        <div class="row">
                            <div class="col-12">
                                <p>Emissão realizada por: <strong> Nome do Funcionário </strong></p>
                            </div>
                        </div>
                    </footer>
                </div>
                <!-- /.invoice -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- /.content-wrapper -->

