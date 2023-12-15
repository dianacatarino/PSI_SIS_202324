<?php

namespace frontend\controllers;

class FaturasController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionCreate()
    {
        return $this->render('create');
    }

}
