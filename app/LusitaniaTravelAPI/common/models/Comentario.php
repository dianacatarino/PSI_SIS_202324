<?php

namespace common\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "comentarios".
 *
 * @property int $id
 * @property string $titulo
 * @property string $descricao
 * @property string $data_comentario
 * @property int $cliente_id
 * @property int $fornecedor_id
 *
 * @property User $cliente
 * @property Fornecedor $fornecedor
 */
class Comentario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comentarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'descricao', 'data_comentario', 'cliente_id', 'fornecedor_id'], 'required'],
            [['descricao'], 'string'],
            [['data_comentario'], 'safe'],
            [['cliente_id', 'fornecedor_id'], 'integer'],
            [['titulo'], 'string', 'max' => 100],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['cliente_id' => 'id']],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedor::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'descricao' => 'Descricao',
            'data_comentario' => 'Data Comentario',
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
}
