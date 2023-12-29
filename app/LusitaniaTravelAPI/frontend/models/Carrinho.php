<?php

namespace frontend\models;

use common\models\Fornecedor;
use common\models\Reserva;
use common\models\User;

/**
 * This is the model class for table "carrinho".
 *
 * @property int $id
 * @property int $quantidade
 * @property float $preco
 * @property float $subtotal
 * @property int $cliente_id
 * @property int $fornecedor_id
 * @property int $reserva_id
 *
 * @property User $cliente
 * @property Fornecedor $fornecedor
 * @property Reserva $reserva
 */
class Carrinho extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrinho';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantidade', 'preco', 'subtotal', 'cliente_id', 'fornecedor_id', 'reserva_id'], 'required'],
            [['quantidade', 'cliente_id', 'fornecedor_id', 'reserva_id'], 'integer'],
            [['preco', 'subtotal'], 'number'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['cliente_id' => 'id']],
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
            'quantidade' => 'Quantidade',
            'preco' => 'Preco',
            'subtotal' => 'Subtotal',
            'cliente_id' => 'Cliente ID',
            'fornecedor_id' => 'Fornecedor ID',
            'reserva_id' => 'Reserva ID',
        ];
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(User::class, ['id' => 'cliente_id']);
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
}
