<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "userTipo".
 *
 * @property int $idUserTipo
 * @property string $nombreUserTipo
 * @property int $estadoUserTipo
 * @property string $creacionUserTipo
 *
 * @property User[] $users
 */
class UserTipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userTipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombreUserTipo', 'creacionUserTipo'], 'required'],
            [['estadoUserTipo'], 'integer'],
            [['creacionUserTipo'], 'safe'],
            [['nombreUserTipo'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idUserTipo' => 'Id User Tipo',
            'nombreUserTipo' => 'Nombre User Tipo',
            'estadoUserTipo' => 'Estado User Tipo',
            'creacionUserTipo' => 'Creacion User Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['idUserTipo' => 'idUserTipo']);
    }
}
