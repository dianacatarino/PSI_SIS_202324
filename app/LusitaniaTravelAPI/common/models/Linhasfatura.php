<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "linhasfaturas".
 *
 * @property int $id
 * @property int $quantidade
 * @property float $precounitario
 * @property float $subtotal
 * @property float $iva
 * @property int $fatura_id
 * @property int $linhasreservas_id
 *
 * @property Fatura $fatura
 * @property Linhasreserva $linhasreservas
 */
class Linhasfatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linhasfaturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantidade', 'precounitario', 'subtotal', 'iva', 'fatura_id', 'linhasreservas_id'], 'required'],
            [['quantidade', 'fatura_id', 'linhasreservas_id'], 'integer'],
            [['precounitario', 'subtotal', 'iva'], 'number'],
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fatura::class, 'targetAttribute' => ['fatura_id' => 'id']],
            [['linhasreservas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Linhasreserva::class, 'targetAttribute' => ['linhasreservas_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quantidade' => 'Quantidade',
            'precounitario' => 'Precounitario',
            'subtotal' => 'Subtotal',
            'iva' => 'Iva',
            'fatura_id' => 'Fatura ID',
            'linhasreservas_id' => 'Linhasreservas ID',
        ];
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatura()
    {
        return $this->hasOne(Fatura::class, ['id' => 'fatura_id']);
    }

    /**
     * Gets query for [[Linhasreservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasreservas()
    {
        return $this->hasOne(Linhasreserva::class, ['id' => 'linhasreservas_id']);
    }

    /**
     * Calcula o IVA com base no subtotal.
     * @param float $subtotal
     * @return float
     */
    public function calcularIva($subtotal)
    {
        $percentagemIva = 0.06; // 6%
        return $subtotal * $percentagemIva;
    }
}
