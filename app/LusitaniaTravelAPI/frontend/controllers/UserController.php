<?php

namespace frontend\controllers;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDefinicoes()
    {
        return $this->render('definicoes');
    }

}
