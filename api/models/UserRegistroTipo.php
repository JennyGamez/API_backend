<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "userRegistroTipo".
 *
 * @property int $idUserRegistroTipo
 * @property string $nombreUserRegistroTipo
 * @property int $estadoUserRegistroTipo
 * @property string $creacionUserRegistroTipo
 *
 * @property UserRegistro[] $userRegistros
 */
class UserRegistroTipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userRegistroTipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombreUserRegistroTipo', 'creacionUserRegistroTipo'], 'required'],
            [['estadoUserRegistroTipo'], 'integer'],
            [['creacionUserRegistroTipo'], 'safe'],
            [['nombreUserRegistroTipo'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idUserRegistroTipo' => 'Id User Registro Tipo',
            'nombreUserRegistroTipo' => 'Nombre User Registro Tipo',
            'estadoUserRegistroTipo' => 'Estado User Registro Tipo',
            'creacionUserRegistroTipo' => 'Creacion User Registro Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRegistros()
    {
        return $this->hasMany(UserRegistro::className(), ['idUserRegistroTipo' => 'idUserRegistroTipo']);
    }
}
