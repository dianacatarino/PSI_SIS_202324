<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reservas".
 *
 * @property int $id
 * @property string|null $tipo
 * @property string $checkin
 * @property string $checkout
 * @property int $numeroquartos
 * @property int $numeroclientes
 * @property float $valor
 * @property int $cliente_id
 * @property int $funcionario_id
 * @property int $fornecedor_id
 *
 * @property User $cliente
 * @property Confirmacao[] $confirmacoes
 * @property Fatura[] $faturas
 * @property Fornecedor $fornecedor
 * @property User $funcionario
 * @property Linhasreserva[] $linhasreservas
 */
class Reserva extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reservas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo'], 'string'],
            [['checkin', 'checkout', 'numeroquartos', 'numeroclientes', 'valor', 'cliente_id', 'funcionario_id', 'fornecedor_id'], 'required'],
            [['checkin', 'checkout'], 'safe'],
            [['numeroquartos', 'numeroclientes', 'cliente_id', 'funcionario_id', 'fornecedor_id'], 'integer'],
            [['valor'], 'number'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['cliente_id' => 'id']],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedor::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
            [['funcionario_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['funcionario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo' => 'Tipo',
            'checkin' => 'Checkin',
            'checkout' => 'Checkout',
            'numeroquartos' => 'Numeroquartos',
            'numeroclientes' => 'Numeroclientes',
            'valor' => 'Valor',
            'cliente_id' => 'Cliente ID',
            'funcionario_id' => 'Funcionario ID',
            'fornecedor_id' => 'Fornecedor ID',
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
     * Gets query for [[Confirmacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfirmacoes()
    {
        return $this->hasMany(Confirmacao::class, ['reserva_id' => 'id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['reserva_id' => 'id']);
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
     * Gets query for [[Funcionario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFuncionario()
    {
        return $this->hasOne(User::class, ['id' => 'funcionario_id']);
    }

    /**
     * Gets query for [[Linhasreservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasreservas()
    {
        return $this->hasMany(Linhasreserva::class, ['reservas_id' => 'id']);
    }

    public static function selectAlojamentos()
    {
        return Fornecedor::find()->select(['nome_alojamento', 'id'])->indexBy('id')->column();
    }

    public static function selectClientes()
    {
        return Profile::find()
            ->select(['name', 'user_id'])
            ->where(['role' => 'cliente'])
            ->indexBy('user_id')
            ->column();
    }

    public static function selectFuncionarios()
    {
        return Profile::find()
            ->select(['name', 'user_id'])
            ->where(['role' => 'funcionario'])
            ->indexBy('user_id')
            ->column();
    }
}
