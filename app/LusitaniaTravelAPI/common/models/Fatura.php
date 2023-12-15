<?php

namespace common\models;

use common\models\Reserva;
use backend\models\Empresa;
use Yii;

/**
 * This is the model class for table "faturas".
 *
 * @property int $id
 * @property float $totalf
 * @property float $totalsi
 * @property float $iva
 * @property int $empresa_id
 * @property int $reserva_id
 *
 * @property Empresa $empresa
 * @property Linhasfatura[] $linhasfaturas
 * @property Reserva $reserva
 */
class Fatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['totalf', 'totalsi', 'iva', 'empresa_id', 'reserva_id'], 'required'],
            [['totalf', 'totalsi', 'iva'], 'number'],
            [['empresa_id', 'reserva_id'], 'integer'],
            [['empresa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresa::class, 'targetAttribute' => ['empresa_id' => 'id']],
            [['reserva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reserva::class, 'targetAttribute' => ['reserva_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'totalf' => 'Totalf',
            'totalsi' => 'Totalsi',
            'iva' => 'Iva',
            'empresa_id' => 'Empresa ID',
            'reserva_id' => 'Reserva ID',
        ];
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresa::class, ['id' => 'empresa_id']);
    }

    /**
     * Gets query for [[Linhasfaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhasfatura::class, ['fatura_id' => 'id']);
    }

    /**
     * Gets query for [[Reserva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReserva()
    {
        return $this->hasOne(Reserva::class, ['id' => 'reserva_id']);
    }
}
