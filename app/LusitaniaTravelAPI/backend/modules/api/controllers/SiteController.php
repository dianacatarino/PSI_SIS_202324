<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;
use yii\web\Response;
use Yii;

class SiteController extends Controller
{
    public $enableCsrfValidation = false; // Desativar a validação CSRF para fins de exemplo

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'user'    => 'lusitaniatravel',
            'pass'    => 'admin123',
            'token'   => 'j8th_N_UlbaTSb2sCmagTNTP-lD28syt',
            'message' => 'Lusitania Travel API',
            'status'  => 'Connected',
        ];

        return $response;
    }
}
?>
