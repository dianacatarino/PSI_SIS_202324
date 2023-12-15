<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "fornecedores".
 *
 * @property int $id
 * @property string $responsavel
 * @property string $tipo
 * @property string $nome_alojamento
 * @property string $localizacao_alojamento
 * @property string|null $acomodacoes_alojamento
 *
 * @property Avaliacao[] $avaliacoes
 * @property Comentario[] $comentarios
 * @property Confirmacao[] $confirmacoes
 * @property Imagem[] $imagens
 */
class Fornecedor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fornecedores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responsavel', 'tipo', 'nome_alojamento', 'localizacao_alojamento'], 'required'],
            [['responsavel', 'nome_alojamento'], 'string', 'max' => 30],
            [['tipo'], 'string', 'max' => 20],
            [['localizacao_alojamento'], 'string', 'max' => 50],
            [['acomodacoes_alojamento'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responsavel' => 'Responsavel',
            'tipo' => 'Tipo',
            'nome_alojamento' => 'Nome Alojamento',
            'localizacao_alojamento' => 'Localizacao Alojamento',
            'acomodacoes_alojamento' => 'Acomodacoes Alojamento',
        ];
    }

    /**
     * Gets query for [[Avaliacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacoes()
    {
        return $this->hasMany(Avaliacao::class, ['fornecedor_id' => 'id']);
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentario::class, ['fornecedor_id' => 'id']);
    }

    /**
     * Gets query for [[Confirmacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfirmacoes()
    {
        return $this->hasMany(Confirmacao::class, ['fornecedor_id' => 'id']);
    }

    /**
     * Gets query for [[Imagens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagens()
    {
        return $this->hasMany(Imagem::class, ['fornecedor_id' => 'id']);
    }

    /**
     * Gets query for [[Reservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reserva::class, ['fornecedor_id' => 'id']);
    }

    public function search($params)
    {
        $query = self::find();

        // Add conditions based on the search parameters
        $query->andFilterWhere(['like', 'responsavel', $this->responsavel])
            ->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'nome_alojamento', $this->nome_alojamento])
            ->andFilterWhere(['like', 'localizacao_alojamento', $this->localizacao_alojamento])
            ->andFilterWhere(['like', 'acomodacoes_alojamento', $this->acomodacoes_alojamento]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
