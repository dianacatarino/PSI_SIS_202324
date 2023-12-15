<?php

namespace common\models;

use common\models\Reserva;
use Yii;

/**
 * This is the model class for table "confirmacoes".
 *
 * @property int $id
 * @property string|null $estado
 * @property string|null $dataconfirmacao
 * @property int $reserva_id
 * @property int $fornecedor_id
 *
 * @property Fornecedor $fornecedor
 * @property Reserva $reserva
 */
class Confirmacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirmacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado'], 'string'],
            [['dataconfirmacao'], 'safe'],
            [['reserva_id', 'fornecedor_id'], 'required'],
            [['reserva_id', 'fornecedor_id'], 'integer'],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedor::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
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
            'estado' => 'Estado',
            'dataconfirmacao' => 'Dataconfirmacao',
            'reserva_id' => 'Reserva ID',
            'fornecedor_id' => 'Fornecedor ID',
        ];
    }

    /**
     * Gets query for [[Fornecedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFornecedor()
    {
        return $this->hasOne(Fornecedor::class, ['id' => 'fornecedor_id']);
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

    public static function selectAlojamentos()
    {
        return Fornecedor::find()->select(['nome_alojamento', 'id'])->indexBy('id')->column();
    }

    public static function selectReservas()
    {
        return Reserva::find()->select(['id'])->indexBy('id')->column();
    }

}
