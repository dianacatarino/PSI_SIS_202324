<?php

namespace backend\models;

use backend\models\Fatura;
use Yii;

/**
 * This is the model class for table "empresas".
 *
 * @property int $id
 * @property string $sede
 * @property float|null $capitalsocial
 * @property string|null $email
 * @property string|null $localidade
 * @property string|null $nif
 * @property string $morada
 *
 * @property Fatura[] $faturas
 */
class Empresa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sede', 'morada'], 'required'],
            [['capitalsocial'], 'number'],
            [['sede', 'localidade'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['nif'], 'string', 'max' => 15],
            [['morada'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sede' => 'Sede',
            'capitalsocial' => 'Capitalsocial',
            'email' => 'Email',
            'localidade' => 'Localidade',
            'nif' => 'Nif',
            'morada' => 'Morada',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['empresa_id' => 'id']);
    }
}
