<?php

namespace common\models;

use common\models\User;
use Yii;
use yii\validators\UniqueValidator;

/**
 * This is the model class for table "avaliacoes".
 *
 * @property int $id
 * @property int $classificacao
 * @property string $data_avaliacao
 * @property int $cliente_id
 * @property int $fornecedor_id
 *
 * @property User $cliente
 * @property Fornecedor $fornecedor
 */
class Avaliacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avaliacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['classificacao', 'data_avaliacao', 'cliente_id', 'fornecedor_id'], 'required'],
            [['classificacao', 'cliente_id', 'fornecedor_id'], 'integer'],
            [['data_avaliacao'], 'safe'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['cliente_id' => 'id']],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedor::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
            ['fornecedor_id', 'validateAvaliacaoUnica'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'classificacao' => 'Classificacao',
            'data_avaliacao' => 'Data Avaliacao',
            'cliente_id' => 'Cliente ID',
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
     * Gets query for [[Fornecedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFornecedor()
    {
        return $this->hasOne(Fornecedor::class, ['id' => 'fornecedor_id']);
    }

    public function validateAvaliacaoUnica($attribute, $params)
    {
        $validator = new UniqueValidator([
            'targetClass' => self::class,
            'targetAttribute' => ['cliente_id', 'fornecedor_id'],
            'message' => 'Você já avaliou este fornecedor.',
        ]);

        $validator->validateAttribute($this, $attribute);
    }
}
